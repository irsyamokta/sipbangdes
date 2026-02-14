<?php

use Illuminate\Support\Facades\Route;
use App\Modules\User\Controllers\UserController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('pengguna')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('permission:users.view')->name('user.index');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:users.create')->name('user.store');
        Route::patch('/{id}', [UserController::class, 'update'])->middleware('permission:users.edit')->name('user.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('permission:users.delete')->name('user.destroy');
    });
});