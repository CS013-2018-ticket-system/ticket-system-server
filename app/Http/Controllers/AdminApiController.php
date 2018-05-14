<?php

namespace App\Http\Controllers;

use App\Events\RefundReviewedEvent;
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
}
