<?php
Route::get('/', function () {
    return redirect('/index');
});

$this->get('index', 'HomeController@index');
Route::resource('company', 'CompanyController');
$this->get('get_company', 'CompanyController@get_company');
Auth::routes();
    
    Route::group([ 'middleware' => 'admin' ] , function () {
        Route::resource('user','UserController');
    });
    
    Route::get('/home' , 'HomeController@index')->name('home');        
    ?>