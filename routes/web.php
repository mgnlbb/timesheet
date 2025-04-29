<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\TimesheetExportController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AdminTimesheetController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Profile dasar
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile tambahan (user profile)
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/create', [UserProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [UserProfileController::class, 'store'])->name('profile.store');
    Route::patch('/profile/update', [UserProfileController::class, 'update'])->name('profile.user_update');

    // Timesheet
    Route::get('/timesheet/{year}/{month}', [TimesheetController::class, 'showMonthly'])->name('timesheet.monthly');
    Route::post('/timesheet', [TimesheetController::class, 'store'])->name('timesheet.store');
    Route::post('/timesheet/import', [TimesheetController::class, 'importExcel'])->name('timesheet.import');
    Route::get('/timesheet/pdf/{year}/{month}', [TimesheetController::class, 'exportPdf'])->name('timesheet.export.pdf');
    Route::get('/timesheet/export-excel', [TimesheetExportController::class, 'exportExcel'])->name('timesheet.export.excel');

});
// Admin routes
Route::middleware('is_admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::get('/timesheets', [AdminTimesheetController::class, 'index'])->name('timesheets.index');
});

require __DIR__.'/auth.php';
