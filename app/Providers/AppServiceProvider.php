<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


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
        Validator::extend('no_hidden_spaces', function ($attribute, $value, $parameters, $validator) {
            // Remove hidden white spaces from the value
            $cleanedValue = preg_replace('/\s+/', '', $value);

            // Compare the cleaned value with the original value
            return $cleanedValue === $value;
        });
    }
}
