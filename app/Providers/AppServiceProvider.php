<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\User;
use App\Models\Status;
use App\Models\Profile;
use App\Models\Calendar;
use App\Models\Category;
use App\Models\Frequency;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // * artinya mengirim data ke semua view
        View::composer('*', function ($view) {
            $view->with('profiles', Profile::where('user_id', Auth::id())->first());
        });//untuk menyimpan semua data, lebih baik gunakan nama variabel dalam bentuk jamak (profiles)
            //di view gunakan $profiles
            
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

    }
}
//done
