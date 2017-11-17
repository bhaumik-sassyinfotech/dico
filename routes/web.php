<?php
    Route::get('/' , function () {
        return redirect('/index');
    });
    
    $this->get('index' , 'HomeController@index');

//$this->get('logout', 'Auth\LoginController@logout')->name('auth.logout');
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
        Route::resource('user' , 'UserController');
        Route::get('edit_profile' , 'UserController@edit_profile');
        Route::post('update_profile' , 'UserController@update_profile');
        Route::post('security_update_profile' , 'UserController@security_update_profile');
        Route::post('changepassword_update_profile','UserController@changepassword_update_profile');
        Route::post('notification_update_profile','UserController@notification_update_profile');
        Route::get('follow/{id}' , 'UserController@follow');
        
        /*Group*/
        Route::match(['get','post'],'group/list','GroupController@groupListing');
        Route::match(['get','post'],'group/editUsers','GroupController@groupUsersEdit');
        Route::post('group/companyUsers','GroupController@companyUsers');
        Route::resource('group' , 'GroupController');
    });
    
    Route::get('/home' , 'HomeController@index')->name('home');
?>