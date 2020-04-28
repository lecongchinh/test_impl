<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Ixudra\Curl\Facades\Curl;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $userService;
    protected $authService;
    public function __construct()
    {
        $this->userService = new UserService();
        $this->authService = new AuthService();
    }

    public function redirectToProvider()
    {
        return redirect('https://github.com/login/oauth/authorize?client_id='.env('GITHUB_CLIENT_ID'));
    }

    public function handleProviderCallback(Request $request)
    {

        $provider = 'github';
        $infor = $this->authService->handleProviderCallback($request);
        $infor = json_decode($infor);
        $gitUser = $this->userService->getUserFromGithub($infor->access_token);
        $gitUser = json_decode($gitUser);
        try {
            if($provider == 'github') {
                $user = $this->userService->createUserGithub($gitUser,$provider, $infor->access_token);
            }

            Auth::login($user, true);
        } catch (\Exception $e) {
            return Redirect::back()->withErrors(['message' => 'Login failed, please try again !']);
        }

        return redirect()->route('user.info')->with(['message' => 'Login complete']);
    }

}
