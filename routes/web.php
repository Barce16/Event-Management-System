<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Customer\EventController as CustomerEventController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\EnsureCustomer;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', fn() => view('dashboard'))
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

            // ---- Management ----
            Route::prefix('management')->name('management.')->group(function () {

                Route::get('/', [AdminController::class, 'managementIndex'])->name('index');

                // Route::get('vendors', [VendorController::class, 'index'])->name('vendors.index');
                // Route::get('vendors/{vendor}', [VendorController::class, 'show'])->name('vendors.show');
                // Route::get('vendors/{vendor}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
                // Route::patch('vendors/{vendor}', [VendorController::class, 'update'])->name('vendors.update');

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

    Route::get('/payments', fn() => view('payments.index'))->name('payments.index');
    Route::get('/reports/monthly', fn() => view('reports.monthly'))->name('reports.monthly');
});

require __DIR__ . '/auth.php';
