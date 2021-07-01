<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    [
    'middleware' => 'auth:sanctum',
    'namespace' => 'Api'
    ],
    function () {
        Route::patch('/auth/email', 'EmailController@update');
        Route::patch('/auth/password', 'PasswordController@update');

        Route::get('/notification-counts', 'NotificationCountsController');
        Route::get('/notifications', 'NotificationsController@index');

        Route::post('/logout', 'AuthController@logout');

        Route::post('/profile-images', 'UserAvatarController@store');
        Route::get('/chat', 'ChatsController@index');
        Route::get('/chat/{user}', 'ChatsController@show');
        Route::get('/chat/{chat}/messages', 'MessagesController@get');
        Route::post('/chat/{chat}/messages', 'MessagesController@store');
        Route::patch('/chat/{chat}/messages/{user}/read', 'MessagesController@update');

    }
);
Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::post('password/forgot', 'Api\ForgotPasswordController');

Broadcast::routes(['middleware' => ['auth:sanctum']]);
