<?php

//use Illuminate\Routing\Route;
//use Illuminate\Support\Facades\Auth;

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
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile/edit', 'ProfileController@edit')->name('profileEdit')->middleware('auth');

Route::put('/profile/edit', 'ProfileController@update')->name('profileUpdate')->middleware('auth');

Route::get('/profile/{slug}', 'ProfileController@profile')->name('profileShow')->middleware('auth');

Route::post('/profile/{slug}', 'PostController@create')->name('createPost')->middleware('auth');

Route::get('/user/{slug}', 'UserController@user')->name('userShow')->middleware('auth');

Route::get('/users', 'UserController@allUsers')->name('userIndex')->middleware('auth');

Route::post('/user/{userId}', 'UserController@follow')->name('userFollow')->middleware('auth');

Route::post('/user/{userId}/friend_request', 'UserController@friendRequest')->name('userFriendRequest')->middleware('auth');

Route::patch('/user/{userId}/accept_friend_request', 'UserController@acceptFriendRequest')->name('userAcceptFriendRequest')->middleware('auth');

////////////////////////////////////
//Route::post('/view', function () {
//    request()->file('example')->store('example');
//});
//
//Route::get('/test-url', function() {
//    return \Illuminate\Support\Facades\Storage::get('');
//});
//
//Route::get('/test', function (){
//    $items = [
//        'name' => 'john',
//        'age' => 21
//    ];
//    return collect([$items])->filter(function ($item) {
//        return $item['age'];
//    });
//});
