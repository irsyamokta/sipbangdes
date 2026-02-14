<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/take-off-sheet', function () {
        return Inertia::render('Modules/TakeOffSheet');
    })->middleware('permission:tos.view')->name('takeoffsheet.index');
});