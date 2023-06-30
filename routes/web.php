<?php

use Illuminate\Support\Str;
use TCG\Voyager\Events\Routing;
use TCG\Voyager\Events\RoutingAdmin;
use TCG\Voyager\Events\RoutingAdminAfter;
use TCG\Voyager\Events\RoutingAfter;
use TCG\Voyager\Facades\Voyager;

Route::get('/test', function () {
    return 'asdfasdf';
});

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

Auth::routes(['register' => false]);

Voyager::routes();

Route::group(['as' => 'voyager.'], function () {
    event(new Routing());

    $namespacePrefix = '\\'.config('voyager.controllers.namespace').'\\';
    Route::group(['middleware' => 'admin.user'], function () use ($namespacePrefix) {
        event(new RoutingAdmin());
        
        // User log
        Route::get('users/{id}/log', 'Voyager\VoyagerUserLogController@indexLog')->name('user.log');
        
        // Filtering the appointments
        Route::post('appointments/filter', 'AppointmentsFilter@index')->name('appointment.filter');
        
        // Dublicating an appointment
        Route::post('appointments/{id}/duplicate', 'AppointmentDuplicationController@store')->name('appointment.duplicate');

        // Mass assignement of appointments to a user
        Route::post('appointment/assign', 'AppointmentAssignementController@update')->name('appointment.assign');
    
        // Reports routes
        Route::get('reports', 'ReportsController@index')->name('reports.index');
        Route::post('reports', 'ReportsController@show');

        // Comments
        // Route::apiResource('comments', 'CommentsController');
        Route::post('comments', 'CommentsController@index')->name('comments.index');
        Route::post('comments/{id}', 'CommentsController@store')->name('comments.store');
        
        // Set appointment sale visit location
        Route::put('appointment/{id}/location', 'AppointmentsSalesVisitLocation@update')->name('appointment.location');

        // Appointment show route
        Route::get('appointments/{appointment}/edit', 'Voyager\VoyagerAppointmentController@edit')
            ->name('appointments.edit')
            ->middleware('sales.agent');

        event(new RoutingAdminAfter());
    });

    event(new RoutingAfter());
});

if (App::environment('production', 'staging')) {
    URL::forceScheme('https');
}