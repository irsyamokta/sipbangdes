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

        Route::prefix('operational')->group(function () {
            Route::post('/', [OperationalCostController::class, 'store'])->middleware('permission:rab.operational.create')->name('operational.store');
            Route::patch('/{id}', [OperationalCostController::class, 'update'])->middleware('permission:rab.operational.edit')->name('operational.update');
            Route::delete('/{id}', [OperationalCostController::class, 'destroy'])->middleware('permission:rab.operational.delete')->name('operational.destroy');
        });

        Route::post('/insight/generate', [GenerateInsightController::class, 'generate'])
            ->middleware('permission:rab.generate.ai')
            ->name('rab.insight.store');
    });
});
