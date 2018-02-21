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

define('ENC_KEY', 'ZF#48sp(');
//define('DATE_FORMAT','d/m/Y');

define('DATE_FORMAT', 'jS M Y');
define('POST_TITLE_LIMIT', 60);
define('POST_DESCRIPTION_LIMIT', 130);
define('POST_DISPLAY_LIMIT', 6);
define('COMMENT_DISPLAY_LIMIT', 6);
define('PROFILE_PATH', 'public/uploads/profile_pic/');
define('GROUP_PATH', 'public/uploads/groups/');
define('DEFAULT_GROUP_IMAGE', 'assets/img/custome-service.png');
define('DEFAULT_PROFILE_IMAGE', 'assets/img/user.png');
define('DEFAULT_ATTACHMENT_IMAGE', 'assets/img/uploadfiles1.PNG');
define('DEFAULT_LOADER_IMAGE', 'assets/img/spinner.gif');
define('IMAGE_PATH', 'assets/img');
define('DATETIME_FORMAT', 'm/d/Y H:i:s');
define('DEFAULT_COMPANY_LOGO', 'assets/img/logo.png');
define('UPLOAD_PATH', 'public/uploads/company_logo/');