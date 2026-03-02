<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Modules\Unit\Controllers\UnitController;
use App\Modules\Material\Controllers\MaterialController;
use App\Modules\Tool\Controllers\ToolController;
use App\Modules\Wage\Controllers\WageController;
use App\Modules\Ahsp\Controllers\AhspController;
use App\Modules\Ahsp\Controllers\AhspMaterialController;
use App\Modules\Ahsp\Controllers\AhspWageController;
use App\Modules\Ahsp\Controllers\AhspToolController;

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
        Route::get('/', [ToolController::class, 'index'])->middleware('permission:tool.view')->name('tool.index');
        Route::post('/', [ToolController::class, 'store'])->middleware('permission:tool.create')->name('tool.store');
        Route::patch('/{id}', [ToolController::class, 'update'])->middleware('permission:tool.edit')->name('tool.update');
        Route::delete('/{id}', [ToolController::class, 'destroy'])->middleware('permission:tool.delete')->name('tool.destroy');
    });

    /* Wages */
    Route::prefix('upah')->group(function () {
        Route::get('/', [WageController::class, 'index'])->middleware('permission:wage.view')->name('wage.index');
        Route::post('/', [WageController::class, 'store'])->middleware('permission:wage.create')->name('wage.store');
        Route::patch('/{id}', [WageController::class, 'update'])->middleware('permission:wage.edit')->name('wage.update');
        Route::delete('/{id}', [WageController::class, 'destroy'])->middleware('permission:wage.delete')->name('wage.destroy');
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
        Route::get('/', [AhspController::class, 'index'])->middleware('permission:ahsp.view')->name('ahsp.index');
        Route::post('/', [AhspController::class, 'store'])->middleware('permission:ahsp.create')->name('ahsp.store');
        Route::patch('/{id}', [AhspController::class, 'update'])->middleware('permission:ahsp.edit')->name('ahsp.update');
        Route::delete('/{id}', [AhspController::class, 'destroy'])->middleware('permission:ahsp.delete')->name('ahsp.destroy');

        Route::prefix('material')->group(function () {
            Route::post('/', [AhspMaterialController::class, 'store'])->middleware('permission:ahsp.create')->name('ahsp.material.store');
            Route::patch('/{id}', [AhspMaterialController::class, 'update'])->middleware('permission:ahsp.edit')->name('ahsp.material.update');
            Route::delete('/{id}', [AhspMaterialController::class, 'destroy'])->middleware('permission:ahsp.delete')->name('ahsp.material.destroy');
        });

        Route::prefix('upah')->group(function () {
            Route::post('/', [AhspWageController::class, 'store'])->middleware('permission:ahsp.create')->name('ahsp.wage.store');
            Route::patch('/{id}', [AhspWageController::class, 'update'])->middleware('permission:ahsp.edit')->name('ahsp.wage.update');
            Route::delete('/{id}', [AhspWageController::class, 'destroy'])->middleware('permission:ahsp.delete')->name('ahsp.wage.destroy');
        });

        Route::prefix('alat')->group(function () {
            Route::post('/', [AhspToolController::class, 'store'])->middleware('permission:ahsp.create')->name('ahsp.tool.store');
            Route::patch('/{id}', [AhspToolController::class, 'update'])->middleware('permission:ahsp.edit')->name('ahsp.tool.update');
            Route::delete('/{id}', [AhspToolController::class, 'destroy'])->middleware('permission:ahsp.delete')->name('ahsp.tool.destroy');
        });
    });
});
