<?php

namespace App\Http\Controllers;

use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function allOrder()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

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

        return redirect()->to("/order/pay/" . $order->id);

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

        if ($order->has_cancelled == true) {
            $order->status = "<font color='red'>已取消</font>";
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
        $order->seat_no = "{$car_no}车 {$seat_no}座";
        $order->save();

        Auth::user()->balance -= $order->price;
        Auth::user()->save();

        return view("order.confirmed")->with(array(
            "success" => true,
            "order" => $order,
        ));
    }
}
