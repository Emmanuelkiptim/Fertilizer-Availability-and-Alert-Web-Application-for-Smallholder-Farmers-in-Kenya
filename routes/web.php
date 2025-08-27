<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\AgrovetController;

Route::get('/', function () {
    return view('auth.register');
});
//DETAILS
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   // Route::get('/profile', [FarmerController::class, 'showFarmerInfo'])->name('farmer.info');

});

Route::get('/dashboard',[UserController::class,'Dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
//farmer registration
Route::middleware(['auth','farmer'])->group(function () {
    Route::get('/register-as-a-farmer', [FarmerController::class, 'create'])->name('farmer.create');
    Route::post('/register-as-a-farmer', [FarmerController::class, 'store'])->name('farmer.store');
    Route::post('/profile/update-phone', [FarmerController::class, 'update'])->name('farmer.update');
});
//agrovet registration
Route::middleware(['auth','agrovet'])->group(function () {
    Route::get('/register-as-an-agrovet', [AgrovetController::class, 'create'])->name('agrovet.create');
    Route::post('/register-as-an-agrovet', [AgrovetController::class, 'store'])->name('agrovet.store');
    Route::post('/agrovet/profile/update', [AgrovetController::class, 'update'])->name('agrovet.update');
});

Route::get('/dashboard',[UserController::class,'Dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   // Route::get('/profile', [FarmerController::class, 'showFarmerInfo'])->name('farmer.info');

});



require __DIR__.'/auth.php';
