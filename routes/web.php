<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return \App\Models\User::query()->exists()
        ? redirect()->route('login')
        : redirect()->route('register');
});

Route::get('/register', [RegistrationController::class, 'create'])
    ->middleware('registration.state:register')
    ->name('register');
Route::post('/register', [RegistrationController::class, 'store'])
    ->middleware('registration.state:register');

Route::get('/login', [LoginController::class, 'create'])
    ->middleware('registration.state:login')
    ->name('login');
Route::post('/login', [LoginController::class, 'store'])
    ->middleware(['registration.state:login', 'mpin.lock']);

Route::middleware(['auth', 'lock.session'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/opening-balance', [DashboardController::class, 'openingBalance'])->name('opening-balance');
    Route::post('/opening-balance', [DashboardController::class, 'storeOpeningBalance']);

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    Route::get('/summary', [SummaryController::class, 'index'])->name('summary');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/change-mpin', [SettingsController::class, 'editMpin'])->name('settings.change-mpin');
    Route::post('/settings/change-mpin', [SettingsController::class, 'updateMpin']);
    Route::get('/settings/export', [SettingsController::class, 'export'])->name('settings.export');
    Route::post('/logout', [SettingsController::class, 'logout'])->name('logout');
});

Route::get('/categories', [CategoryController::class, 'index'])->middleware('auth');
