<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Validate email customize
        Validator::extend('regex_email', function($attribute, $value, $parameters, $validator) {
            if (!is_string($value) && !is_numeric($value)) {
                return false;
            }
            return preg_match($parameters[0], $value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
