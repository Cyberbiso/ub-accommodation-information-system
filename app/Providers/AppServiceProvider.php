<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.app', 'layouts.public'], function ($view) {
            $announcements = collect();
            $notifications = collect();

            if (Schema::hasTable('announcements')) {
                $announcements = Announcement::active()
                    ->when(Auth::check(), function ($query) {
                        $query->where(function ($roleQuery) {
                            $roleQuery->whereNull('target_role')
                                ->orWhere('target_role', Auth::user()->role);
                        });
                    }, function ($query) {
                        $query->whereNull('target_role');
                    })
                    ->take(3)
                    ->get();
            }

            if (Auth::check() && Schema::hasTable('system_notifications')) {
                $notifications = SystemNotification::where('user_id', Auth::id())
                    ->latest()
                    ->take(8)
                    ->get();
            }

            $view->with('sharedAnnouncements', $announcements)
                ->with('sharedNotifications', $notifications);
        });
    }
}
