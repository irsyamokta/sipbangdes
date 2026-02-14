<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/pengguna', function () {
        return Inertia::render('Modules/Users');
    })->middleware('permission:users.view')->name('user.index');
});