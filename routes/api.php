<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('sync/receive', 'SyncController@onlineSys');
Route::post('sync/pix/check/{school}', 'SyncController@onlinePixCheck');
Route::post('sync/pix/upload/{school}', 'SyncController@onlinePixUpload');

Route::post('test/text', function(){
    return ["Hello" => "wordl"];
});