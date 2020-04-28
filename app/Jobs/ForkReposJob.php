<?php

namespace App\Jobs;

use App\Events\ForkRepos;
use App\Services\RepositoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ForkReposJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $repositoryService;
    public $request;
    public $data;
    protected $url;
    public function __construct($request, $data)
    {
        $this->repositoryService = new RepositoryService();
        $this->request = $request;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        DB::beginTransaction();
        try {
            $repos = $this->repositoryService->forkRepos($this->request, $this->data);
            $repos = json_decode($repos);
            $this->repositoryService->updateUrlRepos($this->request, $repos);
            $data = [
                'repos_url' => $repos->clone_url,
                'repos_name' => $repos->name
            ];
            event(new ForkRepos($data));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e;
        }
    }

}
