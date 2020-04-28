<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/auth/redirect/{provider}', 'Clients\AuthController@redirectToProvider')->name('login.github');
Route::get('/callback', 'Clients\AuthController@handleProviderCallback');

Route::get('/check_curl', 'HomeController@getData');

Route::middleware('auth')->group(function() {
    Route::prefix('users')->group(function () {
        Route::get('/info', 'Clients\UserController@showInfor')->name("user.info");
    });
    Route::prefix('github')->group(function () {
        Route::get('/curl/repos/{username}', 'Clients\RepositoryController@curlShowRepository')->name("show.repos");
        Route::post('/curl/repos', 'Clients\RepositoryController@curlFindRepository')->name("find.repos");
        Route::get('/show-repos', 'Clients\RepositoryController@getAll');
        Route::post('/repos', 'Clients\RepositoryController@saveRepo')->name('save.repos');
        Route::post('/fork', 'Clients\RepositoryController@fork');
    });
});

