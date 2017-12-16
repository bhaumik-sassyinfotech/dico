<?php
    Route::get('/' , function () {
        return redirect('/index');
    });
      
    Route::get('index' , 'HomeController@index');
    
    Auth::routes();
    Route::match([ 'get' , 'post' ] , '/first_login' , 'UserSecurityQuestionController@firstLogin')->name('security.firstLogin');
    Route::group([ 'middleware' => 'admin' ] , function () {
        Route::resource('company' , 'CompanyController');
        Route::resource('security_question' , 'SecurityQuestionController');
        Route::resource('employee' , 'EmployeeController');
        Route::get('get_company' , 'CompanyController@get_company');
        Route::get('get_security_question' , 'SecurityQuestionController@get_security_question');
        Route::get('get_employee' , 'EmployeeController@get_company_employee');
        Route::match([ 'get' , 'post' ] , 'user/list' , 'UserController@userListing')->name('user.list');
        Route::match(['get','post'],'/user/getCompanyGroups','UserController@getCompanyGroups')->name('getCompanyGroups');
        Route::any('/test','UserController@test'); // working mail route
        Route::resource('user' , 'UserController');
        Route::get('edit_profile' , 'DashboardController@edit_profile');
        Route::post('update_profile' , 'DashboardController@update_profile');
        Route::post('security_update_profile' , 'UserController@security_update_profile');
        Route::post('changepassword_update_profile','UserController@changepassword_update_profile');
        Route::post('notification_update_profile','UserController@notification_update_profile');
        //Route::get('follow/{id}' , 'UserController@follow');
        //Route::get('unfollow/{id}' , 'UserController@unfollow');
        Route::get('follow/{id}' , 'DashboardController@follow');
        Route::get('unfollow/{id}' , 'DashboardController@unfollow');
        Route::get('view_profile/{id}' , 'DashboardController@view_profile');
        /*Group*/
        Route::match(['get','post'],'group/list','GroupController@groupListing');
        Route::match(['get','post'],'group/editUsers','GroupController@groupUsersEdit');
        Route::post('group/companyUsers','GroupController@companyUsers');
        Route::resource('group' , 'GroupController');
        /*Points*/
        Route::resource('points' , 'PointsController');
        Route::get('get_points' , 'PointsController@get_points');
        /*Post*/
        Route::get('/post/idea_show/{id}','PostController@idea_show')->name('idea.index');
        Route::match(['get','POST'],'/post/idea_store','PostController@idea_store')->name('idea.store');
        Route::match(['get','POST'],'/post/idea_edit/{id}','PostController@idea_edit')->name('idea.edit');
        Route::match(['get','POST'],'/post/change-status','PostController@change_status');
        Route::match(['PUT','POST'],'/post/idea_update/{id}','PostController@idea_update')->name('idea.update');
        Route::match(['get','POST'],'/post/idea_list','PostController@idea_list')->name('idea.list');
        Route::resource('post' , 'PostController');
        Route::get('like_post/{id}' , 'PostController@like_post');
        Route::get('dislike_post/{id}' , 'PostController@dislike_post');
        Route::get('get_post' , 'PostController@get_post');
        Route::get('viewpost/{id}' , 'PostController@viewpost');
        Route::post('savecomment/{id}','PostController@savecomment');
        Route::get('deletecomment/{id}','PostController@deletecomment');
        Route::get('like_comment/{id}' , 'PostController@like_comment');
        Route::get('dislike_comment/{id}' , 'PostController@dislike_comment');
        Route::post('comment_solution' , 'PostController@comment_solution');
        Route::post('comment_reply' , 'PostController@comment_reply');
        Route::get('deletecommentReply/{id}','PostController@deletecommentReply');
        Route::get('tags' , 'PostController@tags');
    
        Route::post('meeting/saveComment/{id}','MeetingController@savecomment');
        Route::get( 'meeting/deleteComment/{id}','MeetingController@deletecomment');
        Route::match(['get','post'],'/meeting/list','MeetingController@meetingList');
        Route::resource('meeting','MeetingController');
    });
    
    Route::get('/home' , 'DashboardController@index')->name('home');
?>