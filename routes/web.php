<?php


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// Route::group(['prefix' => 'admin'], function () {
    
// });

Voyager::routes();