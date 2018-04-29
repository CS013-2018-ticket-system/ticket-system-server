<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefundRequests extends Model
{
    protected $table = "refund_requests";
    protected $fillable = ["user_id", "order_id", "has_confirmed", "confirmed_by"];
}
