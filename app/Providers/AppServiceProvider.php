<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Schema;

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
        error_reporting(0);
        Schema::defaultStringLength(191);
        
        /**
         * 视图composer共享数据
         * 把一个视图合成器同时附加到多个视图
         */
        View::composer(
            'layouts.partials.sidebar', 'App\Http\ViewComposers\MenuComposer'
        );
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
