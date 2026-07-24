<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Endpoint data untuk DataTables (didaftarkan sebelum resource route
    // agar tidak bentrok dengan route users/{user})
    Route::get('/users/data', [UserController::class, 'data'])->name('users.data');

    // CRUD User
    Route::resource('users', UserController::class)
        ->except(['create', 'show']);
});

require __DIR__.'/auth.php';
