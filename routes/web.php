<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/prodi', [ProdiController::class, 'index'])->name('/prodi');
Route::prefix('/prodi')->group(function() {
    Route::get('/', [ProdiController::class, 'index'])->name('prodi');
    Route::get('/create', [ProdiController::class, 'create'])->name('prodi.create');
    Route::post('/save', [ProdiController::class, 'store'])->name('prodi.save');
});

require __DIR__.'/auth.php';
