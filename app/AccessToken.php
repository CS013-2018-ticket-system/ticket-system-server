<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $table = "access_tokens";
    protected $fillable = ["user_id", "access_token"];

    public function user() {
        return $this->hasOne("App\User", "id", "user_id");
    }
}
