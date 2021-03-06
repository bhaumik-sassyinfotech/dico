<?php
define('FROM_EMAIL', 'devsassyinfotech@gmail.com');
define('REPLAY_NAME', 'Dico');

Route::group(['prefix' => Request::segment(1)], function () {
//Route::get('/', function () {
//	return redirect('/index');
//});
//front end url
Route::get('/', 'front\HomeController@index');
Route::get('/front', 'front\HomeController@index');
Route::get('/how-it-works', 'front\HomeController@how_it_works');
Route::get('/why-us', 'front\HomeController@why_us');
Route::get('/package', 'front\HomeController@packages');
Route::get('/faqs', 'front\HomeController@faqs');
Route::POST('/faqsEmail', 'front\HomeController@faqsEmail');

Route::get('/contactUs', 'front\HomeController@contactUs');
Route::get('/users-login', 'front\HomeController@users_login');
Route::get('/privacy-policy', 'front\HomeController@privacypolicy');
Route::get('/teams-condition', 'front\HomeController@teams_condition');
Route::get('/package', 'front\HomeController@packages');

Route::POST('frontLogin', 'front\UsersController@frontLogin');
Route::POST('companyRegister', 'front\UsersController@companyRegister');
Route::get('/registerPackage/{package}','front\HomeController@registerPackage');
Route::get('/front-logout', 'front\UsersController@logout');
Route::POST('/forgotPasswordMail', 'front\UsersController@forgotPasswordMail')->name('forgotPasswordMail');
Route::POST('/updateForgotPassword', 'front\UsersController@updateForgotPassword');

Route::get('/forgotPasswordRequest/{data}', 'front\UsersController@forgotPasswordRequest')->name('forgotPasswordRequest');
Route::get('/noti', 'front\UsersController@noti')->name('noti');
Route::group(['middleware' => 'front'], function () {
    Route::get('/companyProfile', 'front\DashboardController@companyProfile');
});


/***********************End front URL****************************/

Route::get('index', 'HomeController@index')->name('index');

Auth::routes();
Route::match(['get', 'post'], '/first_login', 'UserSecurityQuestionController@firstLogin')->name('security.firstLogin');
Route::match(['get', 'post'], '/saveSettings', 'DashboardController@saveSettings')->name('saveSettings');
Route::get('/adminForgotPassword', 'Auth\ForgotPasswordController@adminForgotPassword')->name('adminForgotPassword');
Route::group(['middleware' => 'admin'], function () {
	/*Route::get('setting', function () {
		return view('settings');
	})->name('setting');*/
        Route::get('setting','DashboardController@setting')->name('setting');
	Route::resource('company', 'CompanyController');
	Route::resource('security_question', 'SecurityQuestionController');
	Route::match(['get', 'post'], '/user/alterStatus', 'UserController@alterStatus'); // user-alter-status
	Route::match(['get', 'post'], '/user/employeeList', 'UserController@getEmployeeListing'); // employee-datatable
	Route::match(['get', 'post'], '/user/employeeGrid', 'UserController@getEmployeeGrid'); // employee-grid view
	Route::match(['get', 'post'], '/user/adminGrid', 'UserController@getGroupAdminGrid'); // admin-grid view
	Route::match(['get', 'post'], '/user/adminList', 'UserController@getGroupAdminList'); // admin-list view
	Route::match(['get', 'post'], '/user/otherManagersList', 'UserController@getOtherManagerList'); // other_managers-list view
	Route::match(['get', 'post'], '/user/otherManagersGrid', 'UserController@getOtherManagerGrid'); // admin-grid view
	Route::resource('employee', 'EmployeeController');
	Route::get('get_company', 'CompanyController@get_company');
	Route::get('get_security_question', 'SecurityQuestionController@get_security_question');
	Route::get('get_employee', 'EmployeeController@get_company_employee');
	Route::match(['get', 'post'], 'user/list', 'UserController@userListing')->name('user.list');
	Route::match(['get', 'post'], '/user/getCompanyGroups', 'UserController@getCompanyGroups')->name('getCompanyGroups');
	Route::any('/test', 'UserController@test'); // working mail route
	Route::resource('user', 'UserController');
	Route::match(['get', 'post'], '/getUserProfile', 'UserController@getUserProfile');
	Route::get('edit_profile', 'DashboardController@edit_profile');
	Route::post('update_profile', 'DashboardController@update_profile');
	Route::post('update_profile_pic', 'DashboardController@update_profile_pic');
	Route::post('security_update_profile', 'UserController@security_update_profile');
	Route::post('changepassword_update_profile', 'UserController@changepassword_update_profile');
	Route::post('notification_update_profile', 'UserController@notification_update_profile');
	//Route::get('follow/{id}' , 'UserController@follow');
	//Route::get('unfollow/{id}' , 'UserController@unfollow');
	Route::get('follow/{id}', 'DashboardController@follow')->name('follow');
	Route::get('unfollow/{id}', 'DashboardController@unfollow')->name('unfollow');
	Route::match(['get', 'post'], '/searchPost', 'DashboardController@searchPost'); // search post on view profile page
	Route::get('view_profile/{id}', 'DashboardController@view_profile')->name('view_profile');
	/*Group*/

	Route::match(['get', 'post'], 'group/addGroup', 'GroupController@addGroup'); // add group via ajax on add user page (super admin)
	Route::match(['get', 'post'], 'group/mygroups', 'GroupController@myGroupGrid');
	Route::match(['get', 'post'], 'group/search', 'GroupController@searchGroup');
	Route::match(['get', 'post'], 'group/list', 'GroupController@groupListing')->name('group.list');
	Route::match(['get', 'post'], 'group/delete_group', 'GroupController@deleteGroup');
	Route::match(['get', 'post'], 'group/addUserByEmailAddress', 'GroupController@addUserByEmailAddress');
	Route::match(['get', 'post'], 'group/uploadGroupPicture', 'GroupController@uploadGroupPicture')->name('group.uploadGroupPicture');
	Route::match(['get', 'post'], 'group/editUsers', 'GroupController@groupUsersEdit');
	Route::post('group/companyUsers', 'GroupController@companyUsers');
	Route::resource('group', 'GroupController');
	/*Points*/
	Route::match(['get', 'post'], 'points/my_group_users', 'PointsController@myGroupUsers');
	Route::match(['get', 'post'], 'points/viewList', 'PointsController@pointsIndex')->name('points.viewList');
	Route::match(['get', 'post'], 'points/listing', 'PointsController@pointsListing')->name('points.listing');
	Route::resource('points', 'PointsController');
	Route::get('get_points', 'PointsController@get_points');
	/*Post*/
	Route::get('/post/idea_show/{id}', 'PostController@idea_show')->name('idea.index');
	Route::match(['get', 'POST'], '/post/idea_store', 'PostController@idea_store')->name('idea.store');
	Route::match(['get', 'POST'], '/post/idea_edit/{id}', 'PostController@idea_edit')->name('idea.edit');
	Route::match(['get', 'POST'], '/post/change-status', 'PostController@change_status');
	Route::match(['PUT', 'POST'], '/post/idea_update/{id}', 'PostController@idea_update')->name('idea.update');
	Route::match(['get', 'POST'], '/post/idea_list', 'PostController@idea_list')->name('idea.list');
	Route::resource('post', 'PostController');
	Route::match(['get', 'POST'], 'deletePost', 'PostController@deletePost')->name('deletePost');
	Route::get('like_post/{id}', 'PostController@like_post');
	Route::get('dislike_post/{id}', 'PostController@dislike_post');
	Route::get('get_post', 'PostController@get_post');
	Route::get('viewpost/{id}', 'PostController@viewpost')->name('viewpost');
	Route::post('savecomment/{id}', 'PostController@savecomment')->name('savecomment');
	Route::get('deletecomment/{id}', 'PostController@deletecomment')->name('deletecomment');
	Route::get('like_comment/{id}', 'PostController@like_comment');
        
        
	Route::get('postnotification', 'PostController@postnotification')->name('postnotification');
	
        Route::get('dislike_comment/{id}', 'PostController@dislike_comment');
	Route::post('comment_solution', 'PostController@comment_solution');
	Route::post('comment_reply', 'PostController@comment_reply');
	Route::get('deletecommentReply/{id}', 'PostController@deletecommentReply');
	Route::get('tags', 'PostController@tags');
	Route::get('edit_challenge/{id}', 'PostController@edit_challenge');

	Route::get('meeting/deleteMeeting/{id}', 'MeetingController@deleteMeeting')->name('deleteMeeting');
	Route::get('meeting/deleteIdeaPost/{id}', 'MeetingController@deleteIdeaPost');
	Route::match(['get', 'post'], 'meeting/finalizeMeeting', 'MeetingController@finalizeMeeting')->name('finalizeMeeting');
	Route::post('meeting/saveComment/{id}', 'MeetingController@savecomment')->name('meeting.saveComment');
	Route::match(['get', 'post'], 'meeting/deleteComment', 'MeetingController@deletecomment')->name('deleteMeetingComment');
	Route::match(['get', 'post'], 'meeting/UpdateComment', 'MeetingController@updateComment')->name('updateMeetingComment');
	Route::match(['get', 'post'], 'meeting/commentReply', 'MeetingController@replyToComment')->name('replyToMeetingComment');
	Route::match(['get', 'post'], '/meeting/list', 'MeetingController@meetingList');
	Route::match(['get', 'post'], '/meeting/leaveMeeting', 'MeetingController@leaveMeeting');
	Route::resource('meeting', 'MeetingController');
	Route::post('loadmorepost', 'PostController@loadmorepost')->name('loadmorepost');
	Route::post('loadmoremypost', 'PostController@loadmoremypost');
	Route::post('loadmoregrouppost', 'PostController@loadmoregrouppost');
	Route::get('tag/{id}', 'TagController@tagpost')->name('tag');
	Route::post('loadmoretagpost', 'TagController@loadmoretagpost');
	Route::post('comment_update', 'PostController@comment_update');
	Route::post('allComments', 'PostController@allComments');
	Route::post('post_flagged', 'PostController@post_flagged');
	Route::post('comment_flagged', 'PostController@comment_flagged');
	Route::post('uploadFile', 'PostController@uploadFile');
	Route::post('getCommentReply', 'PostController@getCommentReply');
	Route::get('deletecommentReply/{id}', 'PostController@deletecommentReply')->name('deletecommentReply');
	Route::post('comment_reply_update', 'PostController@comment_reply_update');
	Route::post('uploadFileMeeting', 'MeetingController@uploadFileMeeting');
	Route::post('deletepost', 'PostController@deletePost');
	Route::post('meeting_comment_update', 'MeetingController@meeting_comment_update');
	Route::get('like_attachment_comment/{id}', 'MeetingController@like_attachment_comment');
	Route::get('dislike_attachment_comment/{id}', 'MeetingController@dislike_attachment_comment');
	Route::get('deleteMeetingComment/{id}', 'MeetingController@deleteMeetingComment')->name('meeting.deleteMeetingComment');
	Route::post('loadmoreallmeeting', 'MeetingController@loadmoreallmeeting');
	Route::post('loadmoremymeeting', 'MeetingController@loadmoremymeeting');
        Route::post('allMeetingComments','MeetingController@allMeetingComments');
        Route::post('meeting_comment_reply','MeetingController@meeting_comment_reply');
        Route::post('getMeetingCommentReply','MeetingController@getMeetingCommentReply');
        Route::post('meeting_comment_reply_update','MeetingController@meeting_comment_reply_update');
        Route::get('deleteMeetingCommentReply/{id}','MeetingController@deleteMeetingCommentReply');
        Route::post('checkEmailExists','DashboardController@checkEmailExists');
        Route::get('editGroup/{id}','GroupController@editGroup')->name('editGroup');
        Route::post('group/groupUpdate','GroupController@groupUpdate');
        
        /*         * *****Block**Section************ */
        Route::resource('blog', 'BlockController');
        Route::GET('blockList', 'BlockController@blockList');
        /*         * *****Block**Section************ */
        Route::resource('packages', 'PackageController');
        Route::GET('packagesList', 'PackageController@packagesList');
        /*         * *****FAQs**Section************ */
        Route::resource('adminfaq', 'FaqsController');
        Route::GET('faqList', 'FaqsController@faqList');
        Route::post('deleteFaqs', 'FaqsController@deleteFaqs')->name('deleteFaqs');

        /*         * *****Contact us**Section************ */
        Route::resource('adminContactUs', 'ContactusController');
        Route::GET('contactUsList', 'ContactusController@contactUsList');
        Route::post('contactDelete', 'ContactusController@contactDelete')->name('contactDelete');

        /*         * *****Settings Section************ */
        Route::resource('settings', 'SettingsController');
        /*         * * Company admin update and  upgread own package * */
        Route::GET('companyEdit', 'CompanyController@companyEdit')->name('companyEdit');
        Route::POST('updateCompany', 'CompanyController@updateCompany')->name('updateCompany');
        Route::POST('packageUpgrade', 'CompanyController@packageUpgrade');

        /* feed back */
        Route::resource('feedback', 'FeedbackController');
        Route::GET('feedbackList', 'FeedbackController@feedbackList');
        Route::POST('deleteFeedback', 'FeedbackController@deleteFeedback')->name('deleteFeedback');
        Route::resource('support', 'SupportController');
        Route::GET('supportList', 'SupportController@supportList');
        Route::POST('deleteSupport', 'SupportController@deleteSupport')->name('deleteSupport');
        
        //Notification on off
        Route::resource('notification', 'NotificationController');
        Route::POST('notificationOnOff', 'NotificationController@notificationOnOff')->name('notificationOnOff');
        
        
});
    Route::get('/home', 'HomeController@index')->name('/home');

});
?>