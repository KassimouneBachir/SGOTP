<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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

/* Route::get('/admin-test', function () {
    return "Espace Admin - Accès autorisé";
})->middleware(['auth', 'isAdmin']); */

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.delete');
});

// routes/web.php
Route::post('/admin/users/{user}/change-role', [UserController::class, 'changeRole'])
     ->name('admin.users.change-role');

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    // ... autres routes
    
    Route::get('/users/export', [UserController::class, 'export'])
         ->name('admin.users.export');
});

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index'); // Nom cohérent
    // ... autres routes
});

require __DIR__.'/auth.php';
