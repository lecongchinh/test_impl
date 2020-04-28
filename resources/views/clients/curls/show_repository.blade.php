@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card col-md-10" style="padding: 0">
                <div class="card-header">
                    <div class="row" style="padding: 0px">
                        <div class="col-md-5">
                            <div>Repository</div>
                        </div>
                        <div class="col-md-7 form_find_repo">
                            <form class="form-inline" method="post" action="{{route("find.repos")}}">
                                @csrf
                                <div class="form-group mx-sm-3 mb-2">
                                    <label for="inputUsername" class="sr-only">Username</label>
                                    <input value="{{!empty($username) ? $username : null}}" type="text" class="form-control" name="username" id="inputUsername" placeholder="Username">
                                </div>
                                <button type="submit" class="btn btn-primary mb-2 btn_find_repository">Find repository</button>
                            </form>
                            @error('username')
                                <p style="color: darkred; padding-left: 15px">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php $position = ($page - 1)*30;  ?>
                    @if(!empty($repos->message) && ($repos->message) == "Not Found")
                        <p>This username it's not exist</p>
                    @else
                        @if(!empty($repos))
                            <div>
                                Number of public repository: {{$num_public_repos}}
                            </div>
                            <div>
                                Number of repository loaded: {{($num_public_repos > $limit * $page) ? $limit * $page : $num_public_repos}}
                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Owner</th>
                                    <th scope="col">Start</th>
                                    <th scope="col">Save</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($repos as $repo)
                                    <?php $position +=1; ?>
                                    <tr>
                                        <th>{{$position}}</th>
                                        <td>{{$repo->name}}</td>
                                        <td>{{$repo->html_url}}</td>
                                        <td>{{$repo->stargazers_count}}</td>
                                        <td>
                                            <div class="btn btn-primary save_repo" data-name="{{$repo->name}}" data-owner="{{$username}}">
                                                <i class="fa fa-database" aria-hidden="true"></i>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Username has no repository</p>
                        @endif
                    @endif
                    @if($num_public_repos >= $page * $limit)
                        <form method="post" action="{{route("find.repos",'page='.($page+1))}}">
                            @csrf
                            <input value="{{!empty($username) ? $username : null}}" type="hidden" class="form-control" name="username">
                            <div style="text-align: center">
                                <button type="submit" class="btn btn-primary">Load more</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
