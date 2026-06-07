<?php

namespace App\Providers;

use App\Models\Guardian;
use App\Models\Homework;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\ActivityLog;
use App\Observers\ActivityObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Bootstrap pagination
        \Illuminate\Pagination\Paginator::useBootstrap();

        // Super Admin bypasses all Gate/Policy checks
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super admin') ? true : null;
        });

        // Register model observers for automatic activity logging
        Student::observe(ActivityObserver::class);
        Teacher::observe(ActivityObserver::class);
        Guardian::observe(ActivityObserver::class);
        User::observe(ActivityObserver::class);
        Homework::observe(ActivityObserver::class);

        // Log auth events (pass userId explicitly — session may not be committed yet)
        Event::listen(Login::class, function (Login $event) {
            ActivityLog::record(
                action: 'login',
                description: 'User "' . $event->user->name . '" logged in',
                model: $event->user,
                userId: $event->user->id,
            );
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                ActivityLog::record(
                    action: 'logout',
                    description: 'User "' . $event->user->name . '" logged out',
                    model: $event->user,
                    userId: $event->user->id,
                );
            }
        });
    }
}
