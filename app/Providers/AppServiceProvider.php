<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use App\Models\Profile;
use App\Models\User;
use App\Models\Category;
use App\Models\Calendar;
use App\Models\Frequency;
use App\Models\Status;
use App\Models\Task;
use App\Models\Notification;

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
        // Mengirim data profile ke semua view
        View::composer('*', function ($view) {
            $view->with('profiles', Profile::all());
        });
        View::composer('*', function ($view) {
            $view->with('users', User::all());
        });
        View::composer('*', function ($view) {
            $view->with('calendars', Calendar::all());
        });
        View::composer('*', function ($view) {
            $view->with('categories', Category::all());
        });
        View::composer('*', function ($view) {
            $view->with('statuses', Status::all());
        });
        View::composer('*', function ($view) {
            $view->with('tasks', Task::all());
        });
        View::composer('*', function ($view) {
            $view->with('frequencies', Frequency::all());
        });
        View::composer('*', function ($view) {
            $view->with('notifications', Notification::all());
        });
        // Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

    }
}
