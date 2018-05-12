<?php

namespace App\Http\Controllers;

use App\Order;
use App\RefundRequests;
use App\Trade;
use Carbon\Carbon;
use Composer\DependencyResolver\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function allOrder()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

        foreach ($orders as $index => $order) {
            $order_clone = clone $order;
            $this->getStatus($order_clone, $order);

            $orders[$index] = $order_clone;
        }

        return view("order/all")->with(array(
            "orders" => $orders,
        ));
    }

    public function addOrder(Request $request)
    {
        $train_code = $request->train_code;
        $from_station = explode("-->", $request->start_end_station)[0];
        $to_station = explode("-->", $request->start_end_station)[1];
        $departure_date = $request->departure_date;
        $start_time = $request->start_time;

        $seat_type = explode("|", $request->seat_type)[0];
        $price = str_replace("¥", "", explode("|", $request->seat_type)[1]);

        $order = new Order(array(
            "user_id" => Auth::user()->id,
            "train_code" => $train_code,
            "from_station" => $from_station,
            "to_station" => $to_station,
            "has_paid" => false,
            "has_cancelled" => false,
            "departure_date" => $departure_date,
            "departure_time" => $start_time,
            "seat_type" => $seat_type,
            "price" => $price,
        ));

        $order->save();

        return Response::json(array(
            "success" => true,
            "order_id" => $order->id,
        ));

    }

    public function cancelOrder(Request $request)
    {
        $order = Order::where('id', $request->order_id)->where('user_id', Auth::user()->id)->where('has_cancelled', false);
        if (!$order->count()) {
            die("Invalid order");
        }

        $order = $order->first();
        if ($order->is_being_cancelled()) {
            return Response::json(array(
                "type" => "info",
                "title" => "审核中",
                "status" => "取消待审核",
                "msg" => "您的取消订单请求正在被审核。<br />若取消成功，票款将返还至您的账户。"
            ));
        }

        if ($order->has_paid) {
            $refund_req = new RefundRequests(array(
                "user_id" => Auth::user()->id,
                "order_id" => $request->order_id,
                "has_confirmed" => false,
            ));
            $refund_req->save();
            $need_verify = true;
            $return = array(
                "type" => "info",
                "title" => "审核中",
                "status" => "取消待审核",
                "msg" => "您的取消订单请求需要被审核。<br />若取消成功，票款将返还至您的账户。"
            );

        } else {
            $order->has_cancelled = true;
            $order->save();
            $need_verify = false;
            $return = array(
                "type" => "success",
                "title" => "成功",
                "status" => "<font color='red'>已取消</font>",
                "msg" => "您的订单已成功取消。"
            );
        }

        return Response::json($return);


    }

    public function getStatus(&$order, &$order_obj)
    {
        if ($order->has_cancelled == true) {
            $order->status = "<font color='red'>已取消</font>";
            $order->can_cancel = false;
        } elseif ($order->is_being_cancelled() == true) {
            $order->status = "取消待审核";
            $order->can_cancel = false;
        } elseif ($order->has_paid == true) {
            $order->status = "<font color='green'>已支付</font>";
            if (Carbon::parse($order->departure_date)->diffInMinutes(Carbon::now()) <= 0) {
                $order->can_cancel = false;
            } else {
                $order->can_cancel = true;
            }
        } else {
            $order->status = "未支付";
            if (Carbon::parse($order->departure_date)->diffInMinutes(Carbon::now()) <= 0) {
                $order->can_cancel = false;
                $order->status = "已取消";
                $order_obj->has_cancelled = true;
                $order_obj->save();
            } else {
                $order->can_cancel = true;
                $order->can_pay = true;
            }
        }
    }

    public function payOrder(Request $request)
    {
        $order = Order::where('id', $request->order_id)->where('user_id', Auth::user()->id);
        if (!$order->count()) {
            die("Invalid order");
        }

        $order_obj = $order->first();
        $order = clone $order_obj;

        $order->can_pay = false;

        $this->getStatus($order, $order_obj);

        return view("order/pay")->with(array(
            "order" => $order,
            "request" => $request,
        ));
    }

    public function confirmPayOrder(Request $request)
    {
        $order = Order::where('id', $request->order_id)->where('user_id', Auth::user()->id)->where('has_paid', false);
        if (!$order->count()) {
            die("Invalid order");
        }

        $order = $order->first();

        if (Auth::user()->balance < $order->price) {
            return view("order.confirmed")->with(array(
                "success" => false,
                "msg" => "您的余额不足。",
            ));
        }

        $car_no = random_int(1, 16);
        $seat_no = random_int(1, 30) . chr(random_int(ord("A"), ord("E")));

        $order->has_paid = true;
        $order->seat_no = $order->seat_type == "无座" ? "" : "{$car_no}车 {$seat_no}座";
        $order->save();

        Auth::user()->balance -= $order->price;
        Auth::user()->save();

        $trade = new Trade(array(
            "user_id" => Auth::user()->id,
            "order_id" => $order->id,
            "amount" => -$order->price,
            "note" => "{$order->departure_date} {$order->train_code}({$order->from_station}-->{$order->to_station}) {$order->seat_type}"
        ));
        $trade->save();

        return view("order.confirmed")->with(array(
            "success" => true,
            "order" => $order,
        ));
    }
}
