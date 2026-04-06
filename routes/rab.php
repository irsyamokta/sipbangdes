<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Rab\Controllers\RabController;
use App\Modules\Rab\Controllers\OperationalCostController;
use App\Modules\Rab\Controllers\GenerateInsightController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('rab')->group(function () {
        Route::get('/', [RabController::class, 'index'])->middleware('permission:rab.view')->name('rab.index');
        Route::post('/comment', [RabController::class, 'action'])->middleware('permission:rab.view')->name('rab.action');
        Route::get('/pdf', [RabController::class, 'pdf'])->middleware('permission:rab.download')->name('rab.pdf');

        Route::prefix('operational')->middleware('permission:rab.create')->group(function () {
            Route::post('/', [OperationalCostController::class, 'store'])->name('operational.store');
            Route::patch('/{id}', [OperationalCostController::class, 'update'])->name('operational.update');
            Route::delete('/{id}', [OperationalCostController::class, 'destroy'])->name('operational.destroy');
        });

        Route::post('/insight/generate', [GenerateInsightController::class, 'generate'])
            ->middleware('permission:rab.create')
            ->name('rab.insight.store');
    });
});
