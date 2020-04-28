<?php
namespace App\Services;
use App\Models\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Library\Request;

class RepositoryService {
    public function getAll() {
        $repos = Repository::where('user_id', Auth::user()->id)->get();
        return $repos;
    }

    public function saveRepo($request) {
        $data = [
            'user_id' => Auth::user()->id,
            'repos_name' => $request->repos_name,
            'repos_owner' => $request->repos_owner
        ];

        $repos = Repository::create($data);

        return $repos;
    }

    public function updateUrlRepos($request, $repos) {
        return Repository::where('repos_name', $request['repos_name'])
            ->where('repos_owner', $request['repos_owner'])
            ->update([
                'url' => $repos->clone_url
            ]);
    }

    public function getOne($request) {
        $repos = Repository::where('user_id', Auth::user()->id)
            ->where('repos_name', $request->repos_name)
            ->first();
        return $repos;
    }

    public function curlRepository($request, $limit) {

        $url = 'https://api.github.com/users/'. $request['username'] . '/repos?per_page='.$limit . '&page=' . $request['page'];
        $response = Http::withHeaders([
            'Authorization' => 'token '.Auth::user()->remember_token.'',
            'Accept' => 'application/vnd.github.mercy-preview+json'
        ])->get($url);

        return $response->body();
    }


public  function forkRepos($request, $data) {
    try {
//        $url = 'https://api.github.com/repos/'.$request['repos_owner'].'/'.$request['repos_name'].'/forks';
////        $dataPost = [
////            'scope' => 'repo, gist',
////            'token_type' => 'bearer',
//////            'access_token' => $data['token']
////        ];
//        $response = Http::withHeaders([
//            'Accept' => 'application/vnd.github.v3+json',
//        ])->withBasicAuth('lecongchinh', '123chinhlatoi97')->post($url);
//        dd(($response->body()), $url);
//        dd(123);
//        return $response->body();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.github.com/repos/'.$request['repos_owner'].'/'.$request['repos_name'].'/forks',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_USERPWD => "username:password",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "User-Agent: ".$data['username'],
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return response()->json([
                'message' => 'Fork repos error !'
            ]);
        }

        curl_close($curl);
        return $response;
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Fork repos error !'
        ]);
    }
}
}
