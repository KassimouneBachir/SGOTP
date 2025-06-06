<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ObjetController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
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


use App\Models\Objet;
use App\Models\User;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');



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

Route::middleware(['auth'])->group(function () {
    Route::resource('objets', ObjetController::class)->except(['show']);
});

// Route publique pour visualisation
//Route::get('objets/{objet}', [ObjetController::class, 'show'])->name('objets.show');
Route::middleware('auth')->group(function () {
    Route::get('/objets/{objet}', [ObjetController::class, 'show'])->name('objets.show');

Route::post('/objets', [ObjetController::class, 'store'])->name('objets.store');

Route::put('/objets/{objet}', [ObjetController::class, 'update'])->name('objets.update');
  // Ajouter ces nouvelles routes
    Route::post('/objets/{objet}/claim', [ObjetController::class, 'claim'])->name('objets.claim');
});



/*Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});*/



Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});


Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read.all');

    // Routes pour les réclamations
    Route::prefix('claims')->group(function () {
        Route::get('/', [ClaimController::class, 'index'])->name('claims.index');
        Route::get('/{claim}', [ClaimController::class, 'show'])->name('claims.show');
        Route::post('/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');
        Route::post('/{claim}/reject', [ClaimController::class, 'reject'])->name('claims.reject');
    });

    // Routes pour les objets
    Route::prefix('objets')->group(function () {
        Route::get('/', [ObjetController::class, 'index'])->name('objets.index');
        Route::get('/create', [ObjetController::class, 'create'])->name('objets.create');
        Route::post('/', [ObjetController::class, 'store'])->name('objets.store');
        Route::get('/{objet}', [ObjetController::class, 'show'])->name('objets.show');
        Route::get('/{objet}/edit', [ObjetController::class, 'edit'])->name('objets.edit');
        Route::put('/{objet}', [ObjetController::class, 'update'])->name('objets.update');
        Route::delete('/{objet}', [ObjetController::class, 'destroy'])->name('objets.destroy');
        Route::post('/{objet}/claim', [ObjetController::class, 'claim'])->name('objets.claim');
    });
});



//////////////////////////////////////////////////////////////////////////////////////////////////////////////


Route::middleware('auth')->group(function () {
    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/{conversation}', [ChatController::class, 'show'])->name('chat.show');
        Route::post('/{conversation}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
        Route::post('/message/{message}/read', [ChatController::class, 'markAsRead'])->name('chat.read');
        Route::post('/message/{message}/reaction', [ChatController::class, 'addReaction'])->name('chat.react');
        Route::delete('/message/{message}', [ChatController::class, 'deleteMessage'])->name('chat.delete');
        Route::post('/{conversation}/typing', [ChatController::class, 'typing'])->name('chat.typing');
         Route::get('/start/{user_id}', [ChatController::class, 'startConversation'])->name('chat.start');
    });
});

// routes/web.php

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/statistics', [App\Http\Controllers\Admin\StatisticsController::class, 'dashboard'])->name('admin.statistics');
    Route::get('/statistics/objects', [App\Http\Controllers\Admin\StatisticsController::class, 'objectsReport'])->name('admin.statistics.objects');
    Route::get('/statistics/claims', [App\Http\Controllers\Admin\StatisticsController::class, 'claimsReport'])->name('admin.statistics.claims');
    Route::get('/statistics/users', [App\Http\Controllers\Admin\StatisticsController::class, 'usersReport'])->name('admin.statistics.users');
});




require __DIR__.'/auth.php';
