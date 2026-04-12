<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Project\Controllers\ProjectController;
use App\Modules\Project\Controllers\ProjectDetailController;
use App\Modules\Project\Controllers\ProgressController;
use App\Modules\Project\Controllers\ProjectExpenditureController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('proyek')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->middleware('permission:project.view')->name('project.index');
        Route::post('/', [ProjectController::class, 'store'])->middleware('permission:project.create')->name('project.store');
        Route::patch('/{id}', [ProjectController::class, 'update'])->middleware('permission:project.edit')->name('project.update');
        Route::delete('/{id}', [ProjectController::class, 'destroy'])->middleware('permission:project.delete')->name('project.destroy');

        Route::prefix('progres')->group(function () {
            Route::get('/{id}', [ProjectDetailController::class, 'show'])->middleware('permission:progress.view')->name('progress.show');
            Route::post('/{id}', [ProgressController::class, 'storeProgress'])->middleware('permission:progress.create')->name('progress.store');
            Route::post('/expenditure/{id}', [ProjectExpenditureController::class, 'store'])->middleware('permission:progress.create')->name('expenditure.store');
            Route::patch('/expenditure/{id}', [ProjectExpenditureController::class, 'update'])->middleware('permission:progress.edit')->name('expenditure.update');
            Route::delete('/expenditure/{id}', [ProjectExpenditureController::class, 'destroy'])->middleware('permission:progress.delete')->name('expenditure.destroy');
        });
    });
});
