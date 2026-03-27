<?php

use Illuminate\Support\Facades\Route;
use App\Modules\TakeOffSheet\Controllers\TakeOffSheetController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('take-off-sheet')->group(function () {
        Route::get('/', [TakeOffSheetController::class, 'index'])->middleware('permission:tos.view')->name('tos.index');
        Route::post('/', [TakeOffSheetController::class, 'store'])->middleware('permission:tos.create')->name('tos.store');
        Route::patch('/{id}', [TakeOffSheetController::class, 'update'])->middleware('permission:tos.edit')->name('tos.update');
        Route::delete('/{id}', [TakeOffSheetController::class, 'destroy'])->middleware('permission:tos.delete')->name('tos.destroy');
    });
});
