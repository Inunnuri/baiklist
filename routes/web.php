<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Task\CalendarController;


//Route login
Route::get('login',[LoginController::class, 'index'])->name('login');
Route::get('login/form',[LoginController::class, 'form'])->name('login.form');
Route::post('login/submit',[LoginController::class, 'login'])->name('login.submit');

//Route logout
Route::get('logout',[LoginController::class, 'logout'])->name('logout');

//Route Register
Route::get('register',[RegisterController::class, 'form'])->name('register');
Route::post('register/submit',[RegisterController::class, 'register'])->name('register.submit');

//Route Home
Route::get('/',[HomeController::class, 'index'])->name('home');

//Route Profile
Route::get('profile',[ProfileController::class, 'form'])->name('profile');
Route::post('profile/update',[ProfileController::class, 'update'])->name('profile.update');

// Route my tasks
Route::get('tasks',[TaskController::class, 'index'])->name('tasks.index');
Route::get('task/form',[TaskController::class, 'form'])->name('task.form');
Route::Post('tasks/add',[TaskController::class, 'add'])->name('tasks.add');
Route::Put('tasks/edit/{id}',[TaskController::class, 'edit'])->name('tasks.edit');
Route::Delete('tasks/delete/{id}',[TaskController::class, 'delete'])->name('tasks.delete');
Route::patch('tasks/{id}/update-status', [TaskController::class, 'updateStatus']);


//Route notifications
Route::get('notifications/read/{id}',[NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('notifications/readAll', [NotificationController::class, 'markAllAsRead'])->name('notifications.Allread');
Route::delete('notifications/delete', [NotificationController::class, 'destroyAllRead'])->name('notifications.delete');



//Route task calendar
Route::get('calendar',[CalendarController::class, 'index'])->name('calendar.index');





?>