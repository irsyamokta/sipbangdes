<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/proyek', function () {
        return Inertia::render('Modules/Project');
    })->middleware('permission:project.view')->name('project.index');
});