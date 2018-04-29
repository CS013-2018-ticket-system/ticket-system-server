<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $table = "trades";
    protected $fillable = ["user_id", "order_id", "amount", "note"];
}
