<?php
namespace App\Services;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserService {
    public function createUserGithub($gitUser, $provider, $token) {
        $user = User::where('provider_id', $gitUser->id)->first();
        if (!$user) {
            $user = User::create([
                'name'     => $gitUser->login,
                'email'    => $gitUser->email,
                'provider' => $provider,
                'provider_id' => $gitUser->id,
                'remember_token' => $token
            ]);
        } else {
            User::where('id', $user->id)->update([
               'remember_token' => $token
            ]);
        }
        return $user;
    }

    public function curlInforUserGithub($username) {

        $url = 'https://api.github.com/users/'.$username;
        $response = Http::withHeaders([
            'Authorization' => 'token '.Auth::user()->remember_token.'',
            'Accept' => 'application/vnd.github.mercy-preview+json'
        ])->get($url);

        return $response->body();
    }

    public function getUserFromGithub($token) {
        $url = "https://api.github.com/user";
        $response = Http::withHeaders([
            'Authorization' => 'token '.$token.'',
            'Accept' => 'application/json'
        ])->get($url);
        return $response->body();
    }
}
