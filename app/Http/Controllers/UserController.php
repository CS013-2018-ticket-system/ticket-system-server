<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('user/home');
    }

    public function getLogOut()
    {
        Auth::logout();
        return redirect()->to("http://jaccount.sjtu.edu.cn/oauth2/logout?redirect_uri=" . url("/"));
    }
}
