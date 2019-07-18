<?php

use Illuminate\Support\Str;
use TCG\Voyager\Events\Routing;
use TCG\Voyager\Events\RoutingAdmin;
use TCG\Voyager\Events\RoutingAdminAfter;
use TCG\Voyager\Events\RoutingAfter;
use TCG\Voyager\Facades\Voyager;

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

Route::group(['as' => 'voyager.'], function () {
    event(new Routing());

    $namespacePrefix = '\\'.config('voyager.controllers.namespace').'\\';
    Route::group(['middleware' => 'admin.user'], function () use ($namespacePrefix) {
        event(new RoutingAdmin());
        
        // testing route for filtering the items
        Route::post('products/filter', 'Voyager\VoyagerProductsController@filter');
        
        // User log
        Route::get('users/{id}/log', 'Voyager\VoyagerUserLogController@indexLog')->name('user.log');
        
        // Filtering the appointments
        Route::post('appointments/filter', 'Voyager\VoyagerAppointmentController@filter')->name('appointment.filter');
        
        // Dublicating an appointment
        Route::post('/appointments/{id}/duplicate', 'AppointmentDuplicationController@store')->name('appointment.duplicate');
    
        // Reports routes
        Route::get('reports', 'ReportsController@index')->name('reports.index');
        Route::post('reports', 'ReportsController@show');

        event(new RoutingAdminAfter());
    });

    event(new RoutingAfter());
});
