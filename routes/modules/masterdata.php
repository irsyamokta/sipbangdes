<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Modules\Unit\Controllers\UnitController;
use App\Modules\Material\Controllers\MaterialController;

Route::middleware(['auth', 'verified'])->group(function () {
    /* Materials */
    Route::prefix('material')->group(function () {
        Route::get('/', [MaterialController::class, 'index'])->middleware('permission:material.view')->name('material.index');
        Route::post('/', [MaterialController::class, 'store'])->middleware('permission:material.create')->name('material.store');
        Route::patch('/{id}', [MaterialController::class, 'update'])->middleware('permission:material.edit')->name('material.update');
        Route::delete('/{id}', [MaterialController::class, 'destroy'])->middleware('permission:material.delete')->name('material.destroy');
    });

    /* Tools */
    Route::prefix('alat')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Modules/Tools');
        })->middleware('permission:tool.view')->name('tool.index');
    });

    /* Wages */
    Route::prefix('upah')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Modules/Wages');
        })->middleware('permission:wage.view')->name('wage.index');
    });

    /* Units */
    Route::prefix('satuan')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->middleware('permission:unit.view')->name('unit.index');
        Route::post('/', [UnitController::class, 'store'])->middleware('permission:unit.create')->name('unit.store');
        Route::patch('/{id}', [UnitController::class, 'update'])->middleware('permission:unit.edit')->name('unit.update');
        Route::delete('/{id}', [UnitController::class, 'destroy'])->middleware('permission:unit.delete')->name('unit.destroy');
    });

    /* Worker Category */
    Route::prefix('kategori-pekerjaan')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Modules/WorkerCategory');
        })->middleware('permission:workercategory.view')->name('workercategory.index');
    });

    /* AHSP */
    Route::prefix('ahsp')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Modules/AHSP');
        })->middleware('permission:ahsp.view')->name('ahsp.index');
    });
});
