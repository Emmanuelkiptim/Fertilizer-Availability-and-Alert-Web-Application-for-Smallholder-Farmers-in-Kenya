<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FarmerController;

Route::get('/', function () {
    return view('auth.register');
});


Route::get('/atest', function () {
    return view('agrovet.testfile');
})->middleware(['auth','verified','agrovet']);


Route::middleware(['auth','farmer'])->group(function () {
    Route::get('/Register/EditFarmer', [FarmerController::class, 'registerFarmer'])->name('farmer.Register/EditFarmer');
    
});

Route::get('/dashboard',[UserController::class,'Dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
