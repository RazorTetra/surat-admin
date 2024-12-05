<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncomingLetterController;
use App\Http\Controllers\OutgoingLetterController;
use App\Http\Controllers\LetterTemplateController;
use App\Http\Controllers\PendingLetterController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Letter Management Routes
    Route::prefix('letters')->name('letters.')->group(function () {
        // Incoming Letters
        Route::resource('incoming', IncomingLetterController::class);
        
        // Letter Templates
        Route::resource('templates', LetterTemplateController::class);
        
        // Outgoing Letters
        Route::resource('outgoing', OutgoingLetterController::class);
        
        // Pending Letters
        Route::resource('pending', PendingLetterController::class);
    });
});

require __DIR__.'/auth.php';