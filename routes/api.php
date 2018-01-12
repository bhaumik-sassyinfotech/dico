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
define('POST_DISPLAY_LIMIT',2);
define('COMMENT_DISPLAY_LIMIT',6);
define('PROFILE_PATH','public/uploads/profile_pic/');
define('GROUP_PATH','public/uploads/groups/');
define('DEFAULT_GROUP_IMAGE','assets/img/custome-service.png');
define('DEFAULT_PROFILE_IMAGE','assets/img/post-userone.PNG');
define('DEFAULT_ATTACHMENT_IMAGE','assets/img/uploadfiles1.PNG');
