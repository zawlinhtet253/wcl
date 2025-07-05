<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PageController::class, 'index']);


// Authenticated routes (middleware 'auth' will protect these)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/{id}', [PageController::class, 'detail'])->name('user.detail');
});

// Login POST route
Route::post('/', [AuthController::class, 'login'])->name('login');
