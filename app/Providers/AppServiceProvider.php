<?php

namespace App\Providers;

use App\Models\Transfer;
use App\Models\User;
use App\Observers\TransferObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

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
        Transfer::observe(TransferObserver::class);
        User::observe(UserObserver::class);
    }
}
