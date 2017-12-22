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

define('ENC_KEY','ZF#48sp(');
define('DATE_FORMAT','d/m/Y');
define('POST_TITLE_LIMIT',50);
define('POST_DESCRIPTION_LIMIT',130);
define('POST_DISPLAY_LIMIT',6);
