<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\AgrovetController;
use App\Http\Controllers\FertilizerController;
use App\Http\Controllers\OrderController;
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
    //view fertilizers
    Route::get('/farmers/fertilizers', [FarmerController::class, 'listFertilizers'])->name('farmers.fertilizers.index');
    Route::get('/farmers/fertilizers/{fertilizer_id}', [FarmerController::class, 'showFertilizer'])->name('farmers.fertilizers.show');
    //orderfertilizer
    Route::get('/fertilizers/{fertilizer_id}/order', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.myOrders');


});
//agrovet registration
Route::middleware(['auth','agrovet'])->group(function () {
    Route::get('/register-as-an-agrovet', [AgrovetController::class, 'create'])->name('agrovet.create');
    Route::post('/register-as-an-agrovet', [AgrovetController::class, 'store'])->name('agrovet.store');
    Route::post('/agrovet/profile/update', [AgrovetController::class, 'update'])->name('agrovet.update');
    //Fertilizer
    Route::get('agrovet/fertilizers', [FertilizerController::class, 'index'])->name('fertilizers.index');
    Route::get('agrovet/fertilizers/create', [FertilizerController::class, 'create'])->name('fertilizers.create');
    Route::post('agrovet/fertilizers', [FertilizerController::class, 'store'])->name('fertilizers.store');
    Route::get('agrovet/fertilizers/{id}', [FertilizerController::class, 'show'])->name('fertilizers.show');
    Route::get('agrovet/fertilizers/{id}/edit', [FertilizerController::class, 'edit'])->name('fertilizers.edit');
    Route::put('agrovet/fertilizers/{id}', [FertilizerController::class, 'update'])->name('fertilizers.update');

});

Route::get('/dashboard',[UserController::class,'Dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   // Route::get('/profile', [FarmerController::class, 'showFarmerInfo'])->name('farmer.info');

});



require __DIR__.'/auth.php';
