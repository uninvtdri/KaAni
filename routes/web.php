<?php

use App\Http\Controllers\AdminAdvisoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfficerAdvisoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/weather/refresh', [DashboardController::class, 'refresh'])->name('weather.refresh');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::get('/advisories', [AdminAdvisoryController::class, 'index'])->name('advisories.index');
    Route::patch('/advisories/{advisory}/approve', [AdminAdvisoryController::class, 'approve'])->name('advisories.approve');
    Route::patch('/advisories/{advisory}/reject', [AdminAdvisoryController::class, 'reject'])->name('advisories.reject');
});

Route::middleware(['auth', 'role:officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'officer'])->name('dashboard');
    Route::get('/advisories', [OfficerAdvisoryController::class, 'index'])->name('advisories.index');
    Route::post('/advisories/draft', [OfficerAdvisoryController::class, 'draft'])->name('advisories.draft');
    Route::post('/advisories', [OfficerAdvisoryController::class, 'store'])->name('advisories.store');
});
