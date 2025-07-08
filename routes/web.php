<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PageController::class, 'index'])->name('index');

// Authenticated routes (middleware 'auth' will protect these)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/detail', [PageController::class, 'detail'])->name('user.detail');
    
    Route::get('/attendance', [AttendanceController::class, 'attendance'])->name('user.attendance');
    Route::post('/attendacne' , [AttendanceController::class, 'store'])->name('attendance.store');

    route::get('/timesheets', [TimesheetController::class, 'index'])->name('user.timesheet');
    route::get('/timesheet', [TimesheetController::class, 'add'])->name('user.timesheet.create');
    route::post('/timesheet', [TimesheetController::class, 'store'])->name('timesheet.store');
});

// Login POST route
Route::post('/', [AuthController::class, 'login'])->name('login');

// Logout POST route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
