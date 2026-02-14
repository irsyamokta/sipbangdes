<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : Inertia::render('Auth/Login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Modules/Dashboard');
})->middleware(['auth', 'verified', 'permission:dashboard.view'])->name('dashboard');

require __DIR__ . '/auth.php';
require __DIR__.'/modules/project.php';
require __DIR__.'/modules/tos.php';
require __DIR__.'/modules/rab.php';
require __DIR__.'/modules/masterdata.php';
require __DIR__.'/modules/user.php';
