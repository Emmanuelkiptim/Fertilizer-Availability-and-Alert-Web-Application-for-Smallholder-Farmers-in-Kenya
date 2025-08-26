<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FarmerController;

Route::get('/', function () {
    return view('auth.register');
});

Route::get('/dashboard',[UserController::class,'Dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','farmer'])->group(function () {
    Route::get('/register-as-a-farmer', [FarmerController::class, 'create'])->name('farmer.create');
    Route::post('/register-as-a-farmer', [FarmerController::class, 'store'])->name('farmer.store');
});

Route::get('/dashboard',[UserController::class,'Dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   // Route::get('/profile', [FarmerController::class, 'showFarmerInfo'])->name('farmer.info');

});



require __DIR__.'/auth.php';
