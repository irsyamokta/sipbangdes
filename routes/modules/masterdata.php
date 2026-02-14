<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

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
        Route::get('/', function () {
            return Inertia::render('Modules/Units');
        })->middleware('permission:unit.view')->name('unit.index');
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