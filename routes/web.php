<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\MahasiswaController;
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

Route::prefix('/prodi')->group(function () {
    Route::get('/', [ProdiController::class, 'index'])->name('prodi');
    Route::get('/create', [ProdiController::class, 'create'])->name('prodi.create');
    Route::post('/save', [ProdiController::class, 'store'])->name('prodi.save');
    Route::get('/edit/{id}', [ProdiController::class, 'edit'])->name('prodi.edit');
    Route::put('/edit/{id}', [ProdiController::class, 'update'])->name('prodi.update');
    Route::delete('/delete/{id}', [ProdiController::class, 'delete'])->name('prodi.delete');
});

Route::prefix('/mahasiswa')->group(function () {
    Route::get('/', [MahasiswaController::class, 'index'])->name('mahasiswa');
    Route::get('/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/save', [MahasiswaController::class, 'save'])->name('mahasiswa.save');
    Route::get('/edit/{id}', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/edit/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/delete/{id}', [MahasiswaController::class, 'delete'])->name('mahasiswa.delete');
});



require __DIR__ . '/auth.php';
