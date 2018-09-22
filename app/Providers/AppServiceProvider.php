<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Channel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // \View::composer('threads.create', function($view){
        //     $view->with('channels', \App\Channel::all());
        // });

        \View::composer('*', function($view){
            $channels = \Cache::rememberForever('channels', function(){
                return Channel::all();
            });
            $view->with('channels', $channels);


            // $view->with('channels', \App\Channel::all());
        });

        // \View::share('channels', \App\Channel::all());\


        //we register our new rule here
        \Validator::extend('spamfree', 'App\Rules\SpamFree@passes');
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->isLocal()){
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            
        }
    }
}
