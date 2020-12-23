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
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    Route::get('chat/{id}', 'ChatController@chatPage')->name('chat.user_chat');
    Route::get('home', 'ChatController@index')->name('chat.home');
    Route::get('users', 'ChatController@index')->name('chat.users');
    Route::post('get-messages', 'ChatController@getMessages')->name('chat.get_messages');
    Route::post('read-messages', 'ChatController@readMessages')->name('chat.read_messages');
    Route::post('send-message', "ChatController@sendMessage")->name('chat.send_message');
});
Route::get('/home', 'HomeController@index')->name('home');
