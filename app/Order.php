<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    protected $fillable = ["user_id", "train_code", "from_station", "to_station", "has_paid", "has_cancelled", "departure_date", "departure_time", "seat_type", "seat_no", "price"];

    public function is_being_cancelled() {
        $request = $this->hasOne("App\RefundRequests", "order_id", "id");
        if ($request->count()) {
            return !($request->first()->has_confirmed);
        } else {
            return false;
        }
    }

    public function user() {
        return $this->hasOne("App\User", "id", "user_id");
    }
}
