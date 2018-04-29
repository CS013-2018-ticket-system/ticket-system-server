<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    protected $fillable = ["user_id", "train_code", "from_station", "to_station", "has_paid", "has_cancelled", "departure_date", "departure_time", "seat_type", "seat_no", "price"];

}
