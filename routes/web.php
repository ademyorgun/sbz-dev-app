<?php

Route::get('/command', function () {
	
	/* php artisan migrate */
    \Artisan::call('migrate:fresh');
    dd("Done");
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// Route::group(['prefix' => 'admin'], function () {
    
// });

Voyager::routes();

