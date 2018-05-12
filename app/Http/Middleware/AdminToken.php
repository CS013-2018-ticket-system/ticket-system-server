<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class AdminToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = Input::all();
        $token = $data['access_token'];

        if ($token != User::where('name', 'admin')->first()->remember_token) {
            return Response::json(array(
                "success" => false,
                "msg" => "登录失效。"
            ));
        }

        return $next($request);
    }
}
