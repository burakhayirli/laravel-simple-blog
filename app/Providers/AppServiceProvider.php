<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Config;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      view()->share('config',Config::find(1));
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
