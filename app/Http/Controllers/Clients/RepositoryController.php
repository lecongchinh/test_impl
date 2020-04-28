<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Jobs\ForkReposJob;
use App\Services\RepositoryService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepositoryController extends Controller
{
    protected $repositoryService;
    protected $userService;
    public function __construct()
    {
        $this->repositoryService = new RepositoryService();
        $this->userService = new UserService();
    }

    public function getAll() {
        $repos = $this->repositoryService->getAll();
        return view('clients.repositories.index', compact('repos'));
    }

    public function saveRepo(Request $request) {

        try {
            $repos = $this->repositoryService->getOne($request);
            if($repos != null) {
                return response()->json([
                    'message' => 'The repository already exists '
                ], 500);
            }
            ($this->repositoryService->saveRepo($request));
            return response()->json([
                'message' => 'Save repository success !'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Save repository error, please try again'
            ], 500);
        }
    }

    public function fork(Request $request) {
        $data = [
            'token' => Auth::user()->remember_token,
            'username' => Auth::user()->name,
        ];
        DB::beginTransaction();
        try {

            $forkReoisJob = new ForkReposJob($request->all(), $data);
            $this->dispatch($forkReoisJob);
            DB::commit();
            return response()->json([
               'message' => 'Fork success !'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Fork error !'
            ], 500);
        }
    }

    public function curlFindRepository(Request $request) {
        $request->validate([
            'username' => 'required'
        ]);
        $username = $request->username;
        if(isset($request->page)) {
            $page = $request->page;
            return redirect()->route('show.repos', compact('username', 'page'));
        }
        return redirect()->route('show.repos', compact('username'));
    }

    public function curlShowRepository(Request $request) {
        $limit = 30;
        $repos = $this->repositoryService->curlRepository($request, $limit);
        $repos = json_decode($repos);
        //information of username
        $info = $this->userService->curlInforUserGithub($request->username);
        $info = json_decode($info);
        if(isset($info->public_repos)) {
            $num_public_repos = $info->public_repos;
        } else {
            $num_public_repos = 0;
        }

        $username = $request->username;
        if(isset($request->page)) {
            $page = $request->page;
        } else {
            $page = 1;
        }
        return view('clients.curls.show_repository', compact('repos', 'username', 'page', 'limit', 'num_public_repos'));
    }
}
