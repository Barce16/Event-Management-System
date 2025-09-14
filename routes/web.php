<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Customer\EventController as CustomerEventController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Staff\ScheduleController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\EnsureCustomer;
use App\Http\Middleware\EnsureStaff;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // ========== CUSTOMER AREA ==========
    Route::middleware(['auth', EnsureCustomer::class])
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {
            Route::resource('events', CustomerEventController::class);
        });

    // ========== STAFF AREA ==========

    Route::middleware(['auth', EnsureStaff::class])
        ->prefix('staff')
        ->name('staff.')
        ->group(function () {
            Route::get('schedule', [ScheduleController::class, 'index'])
                ->name('schedule.index');
            Route::get('schedule/{event}', [ScheduleController::class, 'show'])
                ->name('schedule.show');
        });

    // ========== ADMIN AREA ==========
    Route::prefix('admin')
        ->middleware(CheckAdmin::class)
        ->name('admin.')
        ->group(function () {

            // Users (admin/staff management)
            Route::get('/create-user', [AdminController::class, 'createUserForm'])->name('create-user');
            Route::post('/create-user', [AdminController::class, 'createUser'])->name('create-user.store');
            Route::get('/users', [AdminController::class, 'listUsers'])->name('users.list');
            Route::patch('/users/{user}/block', [AdminController::class, 'block'])->name('users.block');
            Route::patch('/users/{user}/unblock', [AdminController::class, 'unblock'])->name('users.unblock');

            // Events
            Route::resource('events', AdminEventController::class)->only(['index', 'show', 'update', 'destroy']);

            Route::patch('events/{event}/status', [AdminEventController::class, 'updateStatus'])->name('events.status');
            Route::patch('events/{event}/assign-staff', [AdminEventController::class, 'assignStaff'])
                ->name('events.assign-staff');

            // ---- Management ----
            Route::prefix('management')->name('management.')->group(function () {

                Route::get('/', [AdminController::class, 'managementIndex'])->name('index');

                Route::resource('vendors', controller: VendorController::class)
                    ->names('vendors');

                Route::patch('vendors/{package}/toggle', [VendorController::class, 'toggle'])
                    ->name('vendors.toggle');


                Route::resource('packages', controller: PackageController::class)
                    ->names('packages');

                Route::patch('packages/{package}/toggle', [PackageController::class, 'toggle'])
                    ->name('packages.toggle');
            });
        });



    Route::resource('customers', CustomerController::class);
    Route::resource('staff', StaffController::class);

    Route::get('/payments', fn() => view('payments.index'))->name('payments.index');
    Route::get('/reports/monthly', fn() => view('reports.monthly'))->name('reports.monthly');
});

require __DIR__ . '/auth.php';
