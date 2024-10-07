<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\AuthController;

//Route Login
Route::post('/login', [AuthController::class, 'login']); //untuk membuat token
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']); //menghapus token
Route::middleware('auth:sanctum')->post('revoke', [AuthController::class, 'revoke']); //menghapus semua token


// Route my tasks
Route::middleware('auth:sanctum')->group(function(){
  Route::get('tasks',[TaskController::class, 'apiIndex'])->name('tasks.apiIndex');
  Route::post('tasks/add',[TaskController::class, 'add'])->name('tasks.add');
  Route::put('tasks/edit/{id}',[TaskController::class, 'edit'])->name('tasks.edit');
  Route::delete('tasks/delete/{id}',[TaskController::class, 'delete'])->name('tasks.delete');
  // Route::post('tasks/notification/{id}', [TaskController::class, 'notification']);
  // Route::patch('tasks/{id}/update-status', [TaskController::class, 'updateStatus']);
});
?>

