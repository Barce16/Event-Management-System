<?php


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\EventController as CustomerEventController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\InclusionController;
use App\Http\Controllers\Staff\ScheduleController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\EnsureCustomer;
use App\Http\Middleware\EnsureStaff;
use Illuminate\Support\Facades\Route;
use App\Models\Package;

Route::get('/', function () {
    $packages = Package::with([
        'vendors:id,name,category,price',
        'inclusions'
    ])
        ->where('is_active', true)
        ->orderBy('price')
        ->get();
    return view('welcome', compact('packages'));
});


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
            Route::get('customer/events/{event}/payment', [PaymentController::class, 'create'])->name('payments.create');
            Route::post('customer/events/{event}/payment', [PaymentController::class, 'store'])->name('payments.store');
            Route::get('/payments', [PaymentController::class, 'index'])->name('payment-history');
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

            // Generic status update (keep this)
            Route::patch('events/{event}/status', [AdminEventController::class, 'updateStatus'])
                ->name('events.status');

            // Staff assignment update
            Route::get('events/{event}/assign-staff', [AdminEventController::class, 'assignStaffPage'])->name('admin.events.assignStaffPage');
            Route::put('events/{event}/assign-staff', [AdminEventController::class, 'assignStaff'])->name('admin.events.assignStaff');


            Route::put('events/{event}/staff', [AdminEventController::class, 'updateStaff'])
                ->name('events.staff.update');

            Route::get('/event/{event}/guests', [AdminEventController::class, 'guests'])->name('event.guests');
            Route::get('/event/{event}/staffs', [AdminEventController::class, 'staffs'])->name('event.staffs');

            // Special cases
            Route::post('events/{event}/approve', [AdminEventController::class, 'approve'])
                ->name('events.approve');
            Route::post('events/{event}/reject', [AdminEventController::class, 'reject'])
                ->name('events.reject');
            Route::post('events/{event}/confirm', [AdminEventController::class, 'confirm'])
                ->name('events.confirm');
            Route::put('events/{event}/staff', [AdminEventController::class, 'assignStaff'])
                ->name('events.staff.assign');
            Route::post('/event/{event}/addStaff', [AdminEventController::class, 'addStaff'])->name('events.addStaff');
            Route::delete('/admin/events/{event}/removeStaff/{staff}', [AdminEventController::class, 'removeStaff'])->name('admin.events.removeStaff');

            // -- PAYMENTS
            Route::get('/events/{event}/payment/verify', [AdminPaymentController::class, 'verifyPayment'])
                ->name('payment.verification');
            Route::post('/events/{event}/approve-payment', [AdminPaymentController::class, 'approvePayment'])->name('payments.approve');
            Route::post('/events/{event}/reject-payment', [AdminPaymentController::class, 'rejectPayment'])->name('payments.reject');

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

                Route::resource('inclusions', InclusionController::class)
                    ->names('inclusions');
            });

            // ---- Payroll ----
            Route::prefix('payroll')->name('payroll.')->group(function () {
                Route::get('/', [PayrollController::class, 'index'])->name('index');
                Route::get('/lines/{eventId}', [PayrollController::class, 'lines'])->name('lines');
                Route::patch('/mark', [PayrollController::class, 'mark'])->name('mark');
                Route::get('/view-staffs/{eventId}', [PayrollController::class, 'viewStaffs'])->name('viewStaffs');
                Route::post('/mark-paid/{eventId}/{staffId}', [PayrollController::class, 'markAsPaid'])->name('markAsPaid');
            });

            // ---- Report ----
            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('/', [ReportsController::class, 'index'])->name('index');

                // Event reports
                Route::get('/event/generate', [ReportsController::class, 'generateEventReport'])->name('event.generate');

                // Customer reports
                Route::get('/customer/generate', [ReportsController::class, 'generateCustomerReport'])->name('customer.generate');

                // Staff reports
                Route::get('/staff/generate', [ReportsController::class, 'generateStaffReport'])->name('staff.generate');
            });
        });



    Route::resource('customers', CustomerController::class);
    Route::resource('staff', StaffController::class);

    Route::get('/reports/monthly', fn() => view('reports.monthly'))->name('reports.monthly');
});

require __DIR__ . '/auth.php';
