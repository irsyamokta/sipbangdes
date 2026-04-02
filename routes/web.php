<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Modules\Dashboard\Controllers\DashboardController;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : Inertia::render('Auth/Login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'permission:dashboard.view'])->name('dashboard');

require __DIR__.'/auth.php';
require __DIR__.'/project.php';
require __DIR__.'/tos.php';
require __DIR__.'/modules/rab.php';
require __DIR__.'/modules/masterdata.php';
require __DIR__.'/modules/user.php';
