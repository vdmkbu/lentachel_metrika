<?php

namespace App\Providers;

use App\Metrika\Metrika;
use App\Metrika\Report;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; //Import Schema

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Report::class, function ($app) {
            $token = env('METRIKA_TOKEN');
            $id = env('METRIKA_ID');
            return new Report($token, $id, new Metrika($token));
        });

        $this->app->singleton(Metrika::class, function ($app) {
            return new Metrika(env('METRIKA_TOKEN'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       Schema::defaultStringLength(191); //Solved by increasing StringLength
    }
}
