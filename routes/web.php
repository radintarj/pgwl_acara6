<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PolygonsController;
use App\Http\Controllers\PolylinesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/map', [PageController::class, 'peta'])->name('map');

Route::get('/tabel', [PageController::class, 'tabel'])->name('tabel');

// Points
Route::post('/store-points', [PointsController::class, 'store'])->name('points.store');
// Route untuk menghapus point berdasarkan ID
Route::delete('/delete-points/{id}', [PointsController::class, 'destroy'])->name('points.delete');

// Polylines
Route::post('/store-polylines', [PolylinesController::class, 'store'])->name('polylines.store');
Route::delete('/delete-polylines/{id}', [PolylinesController::class, 'destroy'])->name('polylines.delete');

// Polygons
Route::post('/store-polygons', [PolygonsController::class, 'store'])->name('polygons.store');
Route::delete('/delete-polygons/{id}', [PolygonsController::class, 'destroy'])->name('polygons.delete');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
