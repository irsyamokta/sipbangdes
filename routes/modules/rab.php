<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Modules\Rab\Controllers\RabController;
use App\Modules\Rab\Controllers\OperationalCostController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('rab')->group(function () {
        Route::get('/', [RabController::class, 'index'])->middleware('permission:rab.view')->name('rab.index');
        Route::post('/comment', [RabController::class, 'action'])->middleware('permission:rab.view')->name('rab.action');
        Route::get('/pdf', [RabController::class, 'pdf'])->middleware('permission:rab.create')->name('rab.pdf');

        Route::prefix('operational')->middleware('permission:rab.create')->group(function () {
            Route::post('/', [OperationalCostController::class, 'store'])->name('operational.store');
            Route::patch('/{id}', [OperationalCostController::class, 'update'])->name('operational.update');
            Route::delete('/{id}', [OperationalCostController::class, 'destroy'])->name('operational.destroy');
        });
    });
});
