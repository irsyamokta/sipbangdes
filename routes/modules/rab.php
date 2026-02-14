<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rab', function () {
        return Inertia::render('Modules/RAB');
    })->middleware('permission:rab.view')->name('rab.index');
});