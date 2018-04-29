<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $table = "stations";
    protected $fillable = ["station_name", "station_abbr", "station_py", "station_code", "station_id"];

}
