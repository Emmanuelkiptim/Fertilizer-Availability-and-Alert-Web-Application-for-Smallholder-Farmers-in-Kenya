<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\AgrovetController;
use App\Http\Controllers\FertilizerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\FertilizerReportController;
use App\Http\Controllers\Admin\AccountSettingsController;
use App\Http\Controllers\Admin\MapOverviewController;
Route::get('/', function () {
    return view('auth.register');
});
//DETAILS
Route::middleware('auth')->group(function () {
    // Admin fertilizer report
    Route::get('/admin/fertilizer-report', [FertilizerReportController::class, 'index'])->name('admin.fertilizerreport');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route::get('/profile', [FarmerController::class, 'showFarmerInfo'])->name('farmer.info');

});

Route::get('/dashboard', [UserController::class, 'Dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
//farmer routes
Route::middleware(['auth', 'farmer'])->group(function () {
    // Alerts
    Route::get('/farmer/alerts', [App\Http\Controllers\AlertController::class, 'index'])->name('alerts.index');
    Route::post('/farmer/alerts/{id}/read', [App\Http\Controllers\AlertController::class, 'markAsRead'])->name('alerts.markAsRead');
    //farmer registration
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
    Route::get('/my-orders/export-csv', [OrderController::class, 'exportCsv'])->name('orders.exportCsv');
    Route::get('/my-orders/pending', [OrderController::class, 'pendingOrders'])->name('orders.pending');
    Route::get('/my-orders/approved', [OrderController::class, 'approvedOrders'])->name('orders.approved');
    //cancel pending order
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
    //faevourite fertilizer
    Route::post('/favourites/{fertilizer}/toggle', [FavouriteController::class, 'toggle'])->name('favourites.toggle');
    Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites.index');

    // Stripe payment routes
    Route::get('/orders/{orderId}/pay', [OrderController::class, 'createStripeSession'])->name('orders.pay');
    Route::get('/orders/payment-success/{orderId}', function($orderId) {
        return redirect()->route('orders.myOrders')->with('success', 'Payment successful!');
    })->name('orders.paymentSuccess');


});
//agrovet routes
Route::middleware(['auth', 'agrovet'])->group(function () {
    //agrovet registration
    Route::get('/register-as-an-agrovet', [AgrovetController::class, 'create'])->name('agrovet.create');
    Route::post('/register-as-an-agrovet', [AgrovetController::class, 'store'])->name('agrovet.store');
    Route::post('/agrovet/profile/update', [AgrovetController::class, 'update'])->name('agrovet.update');
    //Fertilizer
// Stripe webhook route (no auth middleware)
Route::post('/stripe/webhook', [OrderController::class, 'stripeWebhook']);
    Route::get('agrovet/fertilizers', [FertilizerController::class, 'index'])->name('fertilizers.index');
    Route::get('agrovet/fertilizers/create', [FertilizerController::class, 'create'])->name('fertilizers.create');
    Route::post('agrovet/fertilizers', [FertilizerController::class, 'store'])->name('fertilizers.store');
    Route::get('agrovet/fertilizers/{id}', [FertilizerController::class, 'show'])->name('fertilizers.show');
    Route::get('agrovet/fertilizers/{id}/edit', [FertilizerController::class, 'edit'])->name('fertilizers.edit');
    Route::put('agrovet/fertilizers/{id}', [FertilizerController::class, 'update'])->name('fertilizers.update');
    //agrovet orders
    Route::get('agrovet/orders', [OrderController::class, 'agrovetOrders'])->name('agrovet.orders');
    Route::post('agrovet/orders/{id}/approve', [OrderController::class, 'approveOrder'])->name('orders.approve');
    Route::post('agrovet/orders/{id}/reject', [OrderController::class, 'rejectOrder'])->name('orders.decline');

});
//admin routes
// Route::get('/admin', function () {
//     return view('admin.dashboard');
// })->middleware('auth');
Route::middleware(['auth', 'admin'])->group(function () {
    // Users Management
    Route::get('/users', [AdminDashboardController::class, 'usersManagement'])->name('users.management');
    Route::delete('/users/{id}', [AdminDashboardController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/add-admin', [AdminDashboardController::class, 'addAdmin'])->name('users.addAdmin');
    //order-reports
    Route::get('/admin/order-reports', [AdminDashboardController::class, 'ordersManagement'])->name('admin.orderReports');
    //fertilizer reports
    Route::get('/admin/fertilizers', [AdminDashboardController::class, 'fertilizerindex'])->name('admin.fertilizerreport');
    //map overview
    Route::get('/admin/map-overview', [MapOverviewController::class, 'index'])->name('admin.mapOverview');
    //account settings
    Route::get('/admin/settings', [AdminDashboardController::class, 'accountSettings'])->name('admin.accountSettings');
    // Admin login management
    Route::get('/admin/account-settings', [AccountSettingsController::class, 'loginManagement'])->name('admin.accountSettings');
    Route::post('/admin/terminate-session/{sessionId}', [AccountSettingsController::class, 'terminateSession'])->name('admin.terminateSession');
    


});

Route::get('/dashboard', [UserController::class, 'Dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route::get('/profile', [FarmerController::class, 'showFarmerInfo'])->name('farmer.info');

});



require __DIR__ . '/auth.php';
