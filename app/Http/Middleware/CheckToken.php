<?php

namespace App\Http\Middleware;

use App\AccessToken;
use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class CheckToken
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

        if (!AccessToken::where("access_token", $token)->count()) {
            return Response::json(array(
                "success" => false,
                "msg" => "Invalid token."
            ));
        }

        return $next($request);
    }
}
