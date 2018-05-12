<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminApiController extends Controller
{
    public function __construct()
    {
        $this->middleware("admin_token");
    }

    public function apiUsersAll()
    {
        $users = User::where("name", "<>", "admin")->get();
        return Response::json(array(
            "success" => true,
            "data" => $users,
        ));
    }
}
