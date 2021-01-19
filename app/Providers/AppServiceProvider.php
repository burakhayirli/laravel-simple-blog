<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        //
        /*
        Route::resourceVerbs(
          [
            'create'=>'olustur',
            'edit'=>'guncelle'
          ]
        );
        */
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
