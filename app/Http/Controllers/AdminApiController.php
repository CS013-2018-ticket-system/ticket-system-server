<?php

namespace App\Http\Controllers;

use App\Events\RefundReviewedEvent;
use App\RefundRequests;
use App\Trade;
use App\User;
use Illuminate\Http\Request;
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
            "data" => $users,
        ));
    }

    public function apiReviewRefund(Request $request)
    {
        $refund_id = $request->refund_id;
        $refund = RefundRequests::where("id", $refund_id)->first();

        $refund->has_confirmed = true;
        $refund->confirmed_by = User::where("remember_token", $request->access_token)->first()->id;
        $refund->save();
        $price = $refund->order->price;

        $transaction = new Trade(array(
            "user_id" => $refund->user_id,
            "order_id" => $refund->order_id,
            "amount" => $refund->order->price,
            "note" => "订单取消退款",
        ));
        $transaction->save();

        event(new RefundReviewedEvent($refund));

    }
}
