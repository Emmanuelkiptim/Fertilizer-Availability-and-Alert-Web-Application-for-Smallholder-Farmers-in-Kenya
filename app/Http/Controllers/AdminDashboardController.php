<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Fertilizer;
use App\Models\Order;
class AdminDashboardController extends Controller
{
    //
    public function AdminDashboard()
{
    if (Auth::check() && Auth::user()->role == 'admin') {
        $farmersCount = User::where('role', 'farmer')->count();
        $agrovetsCount = User::where('role', 'agrovet')->count();
        $fertilizersCount = Fertilizer::count();
        $ordersCount = Order::count();

        return view('admin.dashboard', compact('farmersCount', 'agrovetsCount', 'fertilizersCount', 'ordersCount'));
    } else {
        return redirect('/');
    }
}
}
