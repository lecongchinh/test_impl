@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row" style="padding: 0px">
                            <div class="col-md-5">
                                <div>Information</div>
                            </div>
                            <div class="col-md-7 form_find_repo">
                                <form class="form-inline" method="post" action="{{route("find.repos")}}">
                                    @csrf
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label for="inputUsername" class="sr-only">Username</label>
                                        <input type="text" class="form-control" name="username" id="inputUsername" placeholder="Username">
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
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Username</th>
                                    <td>{{!empty($info->login) ? $info->login : "null"}}</td>
                                </tr>
                                <tr>
                                    <th>Avatar</th>
                                    <td>
                                        {{empty($info->avatar_url) ? "null" : ''}}
                                        <img src="{{$info->avatar_url}}"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td>{{!empty($info->type) ? $info->type : "null"}}</td>
                                </tr>
                                <tr>
                                    <th>Company</th>
                                    <td>{{!empty($info->company) ? $info->company : "null"}}</td>
                                </tr>
                                <tr>
                                    <th>Blog</th>
                                    <td>{{!empty($info->blog) ? $info->blog : "null"}}</td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>{{!empty($info->location) ? $info->location : "null"}}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{!empty($info->email) ? $info->email : "null"}}</td>
                                </tr>
                                <tr>
                                    <th>Public repository</th>
                                    <td>{{!empty($info->public_repos) ? $info->public_repos : "null"}}</td>
                                </tr>
                                <tr>
                                    <th>Followers</th>
                                    <td>{{!empty($info->followers) ? $info->followers : "null"}}</td>
                                </tr>
                                <tr>
                                    <th>Following</th>
                                    <td>{{!empty($info->following) ? $info->following : "null"}}</td>
                                </tr>
                                <tr>
                                    <th>Date created</th>
                                    <td>{{!empty($info->created_at) ? $info->created_at : "null"}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
