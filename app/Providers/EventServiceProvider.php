<?php
namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Listeners\LogAdminLoginEvents;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            [LogAdminLoginEvents::class, 'handleLogin'],
        ],
        Logout::class => [
            [LogAdminLoginEvents::class, 'handleLogout'],
        ],
    ];
}
