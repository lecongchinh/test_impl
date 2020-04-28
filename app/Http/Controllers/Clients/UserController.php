<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;
    public function __construct()
    {
        $this->userService = new UserService();
    }
    public function showInfor() {
        $info = $this->userService->curlInforUserGithub(Auth::user()->name);
        $info = json_decode($info);
        return view('clients.users.info', compact('info'));
    }
}
