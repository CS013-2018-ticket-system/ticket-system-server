<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function addOrder(Request $request)
    {
        $train_code = $request->train_code;
        $from_station = explode("-->", $request->start_end_station)[0];
        $to_station = explode("-->", $request->start_end_station)[1];
        $departure_date = $request->departure_date;

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
            "seat_type" => $seat_type,
            "price" => $price,
        ));

        $order->save();

        echo "succ";
        exit(0);

    }
}
