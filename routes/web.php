<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PageController::class, 'index'])->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/2fa/verify', [AuthController::class, 'show2faForm'])->name('2fa.verify');
    Route::post('/2fa/verify', [AuthController::class, 'verify2fa'])->name('2fa.verify.post')->middleware('throttle:5,1');
});
// Authenticated routes with 2FA protection
Route::middleware(['auth', '2fa'])->group(function () {
    // User Dashboard
    Route::get('/user/dashboard', [PageController::class, 'dashboard'])->name('user.dashboard');

    // User Profile
    Route::get('/user/profile', [UserController::class, 'detail'])->name('user.detail');
    Route::get('/user/profile/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/profile/update', [UserController::class, 'update'])->name('user.update');

    // Attendance Routes
    Route::get('/user/attendance', [AttendanceController::class, 'attendance'])->name('user.attendance');
    Route::post('/user/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('user.attendance.checkout');
    Route::get('/user/attendance/auto-check-out', [AttendanceController::class, 'autoCheckOut'])->name('user.attendance.autoCheckOut');
    Route::get('/user/attendances', [AttendanceController::class, 'attendances'])->name('user.attendances');
    Route::get('/user/attendances/add', [AttendanceController::class, 'add'])->name('attendance.add');
    Route::post('/user/attendances/add', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/user/attendances/{attendance}/approve', [AttendanceController::class, 'approve'])->name('attendance.approve');

    // Timesheet Routes
    Route::get('/user/timesheets', [TimesheetController::class, 'index'])->name('user.timesheet');
    Route::get('/user/timesheets/create', [TimesheetController::class, 'create'])->name('user.timesheet.create');
    Route::post('/user/timesheets', [TimesheetController::class, 'store'])->name('timesheet.store');
    Route::get('/user/timesheets/{id}', [TimesheetController::class, 'show'])->name('user.timesheet.show');
    Route::get('/user/timesheets/{id}/edit', [TimesheetController::class, 'edit'])->name('user.timesheet.edit');
    Route::put('/user/timesheets/{id}', [TimesheetController::class, 'update'])->name('timesheet.update');
    Route::delete('/user/timesheets/{id}', [TimesheetController::class, 'destroy'])->name('timesheet.destroy');
    Route::post('/user/timesheets/{timesheet}/approve', [TimesheetController::class, 'approve'])->name('timesheet.approve');

    // 2FA Enable Routes (protected by auth)
    Route::get('/2fa/enable', [AuthController::class, 'enable2fa'])->name('2fa.enable');
    Route::post('/2fa/enable', [AuthController::class, 'confirm2fa'])->name('2fa.enable.post');

    // Admin Routes
    Route::prefix('admin')->group(function () {
        // Admin User Management
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.user.create');
        Route::post('/users', [UserController::class, 'store'])->name('admin.user.store');
        Route::get('/users/{id}', [AdminController::class, 'show'])->name('admin.user.show');
        Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.user.edit');
        Route::patch('/users/{id}', [AdminController::class, 'update'])->name('admin.user.update');
        Route::delete('/users/{id}', [AdminController::class, 'delete'])->name('admin.user.delete');

        // Admin Team Management
        Route::get('/teams', [TeamController::class, 'index'])->name('admin.teams');

        // Admin Client Management
        Route::get('/clients', [ClientController::class, 'index'])->name('admin.clients');
        Route::get('/clients/create', [ClientController::class, 'create'])->name('admin.client.create');
        Route::post('/clients', [ClientController::class, 'store'])->name('admin.client.store');
        Route::get('/clients/{id}', [ClientController::class, 'show'])->name('admin.client.show');
        Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])->name('admin.client.edit');
        Route::patch('/clients/{id}', [ClientController::class, 'update'])->name('admin.client.update');
        Route::delete('/clients/{id}', [ClientController::class, 'delete'])->name('admin.client.delete');
    });
});

// Login and Logout Routes
Route::post('/', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');