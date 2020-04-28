@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row" style="padding: 0px">
                            <div class="col-md-5">
                                <div>Repository</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Link</th>
                                    <th scope="col">Fork</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($repos as $repo)
                                    <tr>
                                        <th>{{$repo->id}}</th>
                                        <td>{{$repo->repos_name}}</td>
                                        <td>{{$repo->repos_owner}}</td>
                                        @if(($repo->url == null) || ($repo->url == ''))
                                            <td class="action_fork_repos">
                                                <div class="btn btn-primary fork_repos" data-repos-owner="{{$repo->repos_owner}}" data-repos-name="{{$repo->repos_name}}">
                                                    <i class="fa fa-code-fork" aria-hidden="true"></i>
                                                </div>
                                            </td>
                                        @else
                                            <td class="action_fork_repos">
                                                <div class="btn btn-success url_repos" onclick="window.open('{{$repo->url}}')">
                                                    <div style="color: white">Link to git</div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
