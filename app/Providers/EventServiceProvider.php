<?php

namespace App\Providers;

use App\Events\TransferReceived;
use App\Listeners\SendTransferReceivedNotification;
use App\Models\Transfer;
use App\Models\User;
use App\Observers\TransferObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TransferReceived::class => [
            SendTransferReceivedNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Transfer::observe(TransferObserver::class);
        User::observe(UserObserver::class);
    }
}
