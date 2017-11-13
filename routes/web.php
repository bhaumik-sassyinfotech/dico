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
        Route::resource('user' , 'UserController');
    });
    
    Route::get('/home' , 'HomeController@index')->name('home');
?>