<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Modules\Unit\Controllers\UnitController;

Route::middleware(['auth', 'verified'])->group(function () {
    /* Materials */
    Route::prefix('material')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Modules/Materials');
        })->middleware('permission:material.view')->name('material.index');
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
