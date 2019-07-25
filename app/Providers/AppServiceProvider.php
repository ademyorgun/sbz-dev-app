<?php

namespace App\Providers;

use App\Appointment;
use App\Observers\AppointmentObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);


        /**
         * Model observers
         */
        Appointment::observe(AppointmentObserver::class);


        /**
         * use https
         */
        if(env('APP_ENV') == ('production')) {
            URL::forceScheme('https');
        }
    }
}
