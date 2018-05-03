<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    const ERR_INVALID_USER = 11001;

    public function index()
    {
        return view('user/home');
    }

    public function getLogOut()
    {
        Auth::logout();
        return redirect()->to("http://jaccount.sjtu.edu.cn/oauth2/logout?redirect_uri=" . url("/"));
    }

    public function apiAdminLogin(Request $request)
    {
        $username = Input::all()["username"];
        $password = Input::all()["password"];
        if (Auth::attempt(array("name" => $username, "password" => $password, "user_type" => 1))) {
            Auth::user()->remember_token = str_random(32);
            Auth::user()->save();
            return Response::json(array(
                'success' => true,
                'token' => Auth::user()->remember_token,
            ));
        } else {
            return Response::json(array(
                'success' => false,
                'msg' => '非法用户',
                'errcode' => UserController::ERR_INVALID_USER,
            ));
        }
    }
}
