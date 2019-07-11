<?php

Route::get('/command', function () {
	
	/* php artisan migrate */
    \Artisan::call('migrate:fresh');
    dd("Done");
});


Route::get('/adminset', function () {
	
	/* php artisan migrate */
    \Artisan::call('voyager:admin superAdmin@super.com');
    dd("Done");
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

});


