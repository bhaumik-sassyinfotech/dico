<?php
Route::get('/', function () {
    return redirect('/index');
});

$this->get('index', 'HomeController@index');
Route::resource('company', 'CompanyController');
Route::resource('security_question', 'SecurityQuestionController');
Route::resource('employee', 'EmployeeController');
$this->get('get_company', 'CompanyController@get_company');
$this->get('get_security_question','SecurityQuestionController@get_security_question');
$this->get('get_employee', 'EmployeeController@get_company_employee');
Auth::routes();
    
    Route::group([ 'middleware' => 'admin' ] , function () {
        Route::match(['get','post'],'user/list','UserController@userListing')->name('user.list');
        Route::resource('user','UserController');
    });
    
    Route::get('/home' , 'HomeController@index')->name('home');        
    ?>