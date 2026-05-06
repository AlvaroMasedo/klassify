<?php

namespace App\Providers;

use App\Models\Resource;
use App\Policies\ResourcePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra les polítiques d'autorització.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Resource::class => ResourcePolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $hasUnreadNotifications = false;

            if (Auth::check()) {
                $hasUnreadNotifications = UserNotification::query()
                    ->where('recipient_id', Auth::id())
                    ->whereNull('read_at')
                    ->exists();
            }

            $view->with('hasUnreadNotifications', $hasUnreadNotifications);
        });
        $this->registerPolicies();
    }
}
