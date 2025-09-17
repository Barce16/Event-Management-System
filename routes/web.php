<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportsController;
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

            // ---- Payroll ----
            Route::prefix('payroll')->name('payroll.')->group(function () {
                Route::get('/', [PayrollController::class, 'index'])->name('index');
                Route::get('/lines', [PayrollController::class, 'lines'])->name('lines');
                Route::patch('/mark', [PayrollController::class, 'mark'])->name('mark');
            });

            // ---- Report ----
            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('/', [ReportsController::class, 'index'])->name('index');

                // Events
                Route::get('/events-by-month', [ReportsController::class, 'eventsByMonth'])->name('events.byMonth');
                Route::get('/events-by-status', [ReportsController::class, 'eventsByStatus'])->name('events.byStatus');
                Route::get('/upcoming', [ReportsController::class, 'upcoming'])->name('events.upcoming');

                // Customers
                Route::get('/customers-by-month', [ReportsController::class, 'customersByMonth'])->name('customers.byMonth');
                Route::get('/top-customers', [ReportsController::class, 'topCustomers'])->name('customers.top');

                // Vendors & Packages
                Route::get('/top-vendors', [ReportsController::class, 'topVendors'])->name('vendors.top');
                Route::get('/package-usage', [ReportsController::class, 'packageUsage'])->name('packages.usage');

                // Staff
                Route::get('/staff-workload', [ReportsController::class, 'staffWorkload'])->name('staff.workload');

                // Payments when ready
                // Route::get('/payments-by-month', [ReportsController::class, 'paymentsByMonth'])->name('payments.byMonth');
                // Route::get('/customer-balances', [ReportsController::class, 'customerBalances'])->name('payments.balances');

                // Optional CSV export shared endpoint (q=report-key)
                Route::get('/export', [ReportsController::class, 'export'])->name('export');
            });
        });



    Route::resource('customers', CustomerController::class);
    Route::resource('staff', StaffController::class);

    Route::get('/payments', fn() => view('payments.index'))->name('payments.index');
    Route::get('/reports/monthly', fn() => view('reports.monthly'))->name('reports.monthly');
});

require __DIR__ . '/auth.php';
