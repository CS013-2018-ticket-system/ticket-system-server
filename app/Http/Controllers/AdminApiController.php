<?php

namespace App\Http\Controllers;

use App\Events\RefundReviewedEvent;
use App\Order;
use App\RefundRequests;
use App\Trade;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AdminApiController extends Controller
{
    public function __construct()
    {
        $this->middleware("admin_token");
    }

    public function apiUsersAll()
    {
        $users = User::where("name", "<>", "admin")->get();
        foreach ($users as $user) {
            $user->id_number = substr($user->id_number, 0, 3) . "***" .
                substr($user->id_number, 6, 2) . "****" . substr($user->id_number, 12, 4) .
                "**";
        }
        return Response::json(array(
            "success" => true,
            "count" => count($users),
            "data" => $users,
        ));
    }

    public function apiReviewRefund(Request $request)
    {
        $refund_id = $request->refund_id;
        $refund = RefundRequests::where("id", $refund_id)->where("has_confirmed", false);

        if (!$refund->count()) {
            return Response::json(array(
                "success" => false,
                "err_msg" => "该订单已取消或找不到该订单。"
            ));
        } else {
            $refund = $refund->first();
        }

        $refund->has_confirmed = true;
        $refund->confirmed_by = User::where("remember_token", $request->access_token)->first()->id;
        $refund->save();
        $price = $refund->order->price;
        $refund->order->has_cancelled = true;
        $refund->order->save();

        $transaction = new Trade(array(
            "user_id" => $refund->user_id,
            "order_id" => $refund->order_id,
            "amount" => $refund->order->price,
            "note" => "订单取消退款",
        ));
        $transaction->save();

        $user = User::where("id", $refund->user_id)->first();
        $user->balance += $refund->order->price;
        $user->save();

        event(new RefundReviewedEvent($refund));

        return Response::json(array(
            "success" => true,
        ));

    }

    public function apiOrdersAll(Request $request)
    {
        $count = $request->count;
        $offset = $request->offset;

        if ($count == null) {
            $count = 10;
        }

        if ($offset == null) {
            $offset = 0;
        }

        $orders = Order::offset($offset)
            ->limit($count)
            ->get();

        return Response::json(array(
            "success" => true,
            "count" => count($orders),
            "page_count" => intval((Order::get()->count()-1) / $count + 1),
            "data" => $orders,
        ));
    }

    public function processRefunds($refunds)
    {
        foreach ($refunds as $refund) {
            $refund->order->user;
        }
        return $refunds;
    }

    public function apiRefundGet(Request $request)
    {
        // return all cancel order requests
        $count = $request->count;
        $offset = $request->offset;
        $type = $request->type;

        switch ($type) {
            case "confirmed":
                $refunds = RefundRequests::where("has_confirmed", true);
                return Response::json(array(
                    "success" => true,
                    "count" => count($refunds->offset($offset)->limit($count)->orderBy("id", "desc")->get()),
                    "page_count" => intval(($refunds->count()-1) / $count + 1),
                    "data" => $this->processRefunds($refunds->offset($offset)->limit($count)->orderBy("id", "desc")->get()),
                ));
            case "unconfirmed":
                $refunds = RefundRequests::where("has_confirmed", false);
                return Response::json(array(
                    "success" => true,
                    "count" => count($refunds->offset($offset)->limit($count)->orderBy("id", "desc")->get()),
                    "page_count" => intval(($refunds->count()-1) / $count + 1),
                    "data" => $this->processRefunds($refunds->offset($offset)->limit($count)->orderBy("id", "desc")->get()),
                ));
            case "all":
                $refunds = RefundRequests::all();
                return Response::json(array(
                    "success" => true,
                    "count" => count(RefundRequests::offset($offset)->limit($count)->orderBy("id", "desc")->get()),
                    "page_count" => intval(($refunds->count()-1) / $count + 1),
                    "data" => $this->processRefunds(RefundRequests::offset($offset)->limit($count)->orderBy("id", "desc")->get()),
                ));
        }


    }
}
