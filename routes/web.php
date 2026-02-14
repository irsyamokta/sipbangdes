<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Modules/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/proyek', function () {
    return Inertia::render('Modules/Project');
})->middleware(['auth', 'verified'])->name('project');

Route::get('/take-off-sheet', function () {
    return Inertia::render('Modules/TakeOffSheet');
})->middleware(['auth', 'verified'])->name('takeoffsheet');

Route::get('/rab', function () {
    return Inertia::render('Modules/RAB');
})->middleware(['auth', 'verified'])->name('rab');

Route::get('/material', function () {
    return Inertia::render('Modules/Materials');
})->middleware(['auth', 'verified'])->name('materials');

Route::get('/alat', function () {
    return Inertia::render('Modules/Tools');
})->middleware(['auth', 'verified'])->name('tools');

Route::get('/upah', function () {
    return Inertia::render('Modules/Wages');
})->middleware(['auth', 'verified'])->name('wages');

Route::get('/satuan', function () {
    return Inertia::render('Modules/Units');
})->middleware(['auth', 'verified'])->name('units');

Route::get('/kategori-pekerjaan', function () {
    return Inertia::render('Modules/WorkerCategory');
})->middleware(['auth', 'verified'])->name('workercategory');

Route::get('/ahsp', function () {
    return Inertia::render('Modules/AHSP');
})->middleware(['auth', 'verified'])->name('ahsp');

Route::get('/pengguna', function () {
    return Inertia::render('Modules/Users');
})->middleware(['auth', 'verified'])->name('users');

require __DIR__.'/auth.php';
