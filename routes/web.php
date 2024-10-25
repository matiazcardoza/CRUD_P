<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Ruta para la página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Ruta para el dashboard, solo accesible para administradores
Route::middleware(['auth', 'role:admin', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Puedes añadir aquí otras rutas protegidas para admin
});

// Ruta para los usuarios regulares
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/home', function () {
        return view('home'); // Esta será la página a la que los usuarios regulares son redirigidos
    })->name('home');
});

// Rutas de perfil para cualquier usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
