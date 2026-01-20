<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('redirect.auth.to.dashboard')->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
});

Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/online-count', [AdminController::class, 'onlineCount'])->name('admin.online.count');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/admins/create', [AdminController::class, 'createAdmin'])->name('admin.admins.create');
    Route::post('/admin/admins', [AdminController::class, 'storeAdmin'])->name('admin.admins.store');
    Route::patch('/admin/admins/{user}', [AdminController::class, 'updateAdmin'])->name('admin.admins.update');
    Route::get('/admin/users-transactions', [AdminController::class, 'userTransactions'])->name('admin.users.transactions');
    Route::get('/admin/analysis', [AdminController::class, 'analysis'])->name('admin.analysis');
    Route::get('/admin/analysis/halaman-baru', [AdminController::class, 'analysisNewPage'])->name('admin.analysis.new');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
});

Route::middleware(['auth', 'regular'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/catat', [TransactionController::class, 'index'])->name('catat');
    Route::get('/budget', [TransactionController::class, 'budget'])->name('budget');
    Route::get('/transactions/all', [TransactionController::class, 'all'])->name('transactions.all');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::post('/transactions/bulk', [TransactionController::class, 'storeBulk'])->name('transactions.bulk.store');
    Route::patch('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/transactions/stats', [TransactionController::class, 'stats'])->name('transactions.stats');
    Route::get('/transactions/distribution', [TransactionController::class, 'distribution'])->name('transactions.distribution');
    Route::get('/transactions/summary', [TransactionController::class, 'summary'])->name('transactions.summary');
    Route::get('/laporan', [TransactionController::class, 'report'])->name('reports');
    Route::get('/transactions/budgets', [TransactionController::class, 'budgetsGet'])->name('transactions.budgets.get');
    Route::post('/transactions/budgets', [TransactionController::class, 'budgetsSave'])->name('transactions.budgets.save');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/pending', [TransactionController::class, 'pending'])->name('pending.index');
    Route::patch('/pending/{id}', [TransactionController::class, 'pendingUpdate'])->name('pending.update');
    Route::delete('/pending/{id}', [TransactionController::class, 'pendingDelete'])->name('pending.delete');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
