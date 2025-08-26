<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile-related routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes for User Management (Only accessible by admins)
    Route::middleware(['auth', CheckAdmin::class])->group(function () {
        Route::get('/admin/create-user', [AdminController::class, 'createUserForm'])->name('admin.create-user');
        Route::post('/admin/create-user', [AdminController::class, 'createUser'])->name('admin.create-user.store');
        Route::get('/admin/users', [AdminController::class, 'listUsers'])->name('admin.users.list');
        Route::patch('/admin/users/{user}/block', [AdminController::class, 'block'])->name('admin.users.block');
        Route::patch('/admin/users/{user}/unblock', [AdminController::class, 'unblock'])->name('admin.users.unblock');
    });

    // --- Placeholder module routes (so your nav links work) ---
    Route::get('/events', fn() => view('events.index'))->name('events.index');
    Route::get('/events/create', fn() => view('events.create'))->name('events.create');


    Route::resource('customers', CustomerController::class);

    Route::get('/payments', fn() => view('payments.index'))->name('payments.index');

    // Reports
    Route::get('/reports/monthly', fn() => view('reports.monthly'))->name('reports.monthly');
});

require __DIR__ . '/auth.php';
