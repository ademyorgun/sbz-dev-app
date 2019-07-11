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


// Route::group(['prefix' => 'admin'], function () {
    
// });


Voyager::routes();

// testing route for filtering the items
Route::post('products/filter', 'Voyager\VoyagerProductsController@filter');

// User log
Route::get('users/{id}/log', 'Voyager\VoyagerUserLogController@indexLog')->name('user.log');

// 
Route::post('appointments/filter', 'Voyager\VoyagerAppointmentController@filter')->name('appointment.filter');
