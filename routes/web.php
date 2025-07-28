<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\UserController;
use App\Models\Timesheet;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PageController::class, 'index'])->name('index');

// Authenticated routes (middleware 'auth' will protect these)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/users/detail', [UserController::class, 'detail'])->name('user.detail');
    Route::get('/users/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/users/update', [UserController::class, 'update'])->name('user.update');
    
    // Attendance routes
    Route::get('/attendances', [AttendanceController::class, 'attendances'])->name('user.attendance');
    Route::post('/attendances/add', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('attendances/{attendance}/approve', [AttendanceController::class, 'approve'])->name('attendance.approve');
    // Timesheet routes - Fixed routing conflicts
    Route::get('/timesheets', [TimesheetController::class, 'index'])->name('user.timesheet');
    Route::get('/timesheet/create', [TimesheetController::class, 'create'])->name('user.timesheet.create');
    Route::post('/timesheet/store', [TimesheetController::class, 'store'])->name('timesheet.store');
    Route::get('/timesheet/{id}', [TimesheetController::class, 'show'])->name('user.timesheet.show');
    Route::get('/timesheet/{id}/edit', [TimesheetController::class, 'edit'])->name('user.timesheet.edit');
    Route::put('/timesheet/{id}', [TimesheetController::class, 'update'])->name('timesheet.update');
    Route::delete('/timesheet/{id}', [TimesheetController::class, 'destroy'])->name('timesheet.destroy');
    Route::post('/timesheets/{timesheet}/approve', [TimesheetController::class, 'approve'])->name('timesheet.approve');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.user.create');
    Route::post('/users/create', [UserController::class, 'store'])->name('admin.user.store');
    Route::get('/users/{id}', [AdminController::class, 'show'])->name('admin.user.show');
    Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.user.edit');
    Route::patch('/users/{id}', [AdminController::class, 'update'])->name('admin.user.update');
    Route::delete('/users/{id}', [AdminController::class, 'delete'])->name('admin.user.delete');

    Route::get('/teams', [TeamController::class, 'index'])->name('admin.teams');
    
    Route::get('/clients', [ClientController::class, 'index'])->name('admin.clients');

});

// Login POST route
Route::post('/', [AuthController::class, 'login'])->name('login');

// Logout POST route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');