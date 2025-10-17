<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderReportController extends Controller
{
    public function index(Request $request)
    {
        // Order Summary Report (by day)
        $orderSummary = Order::select(
            DB::raw('DATE(created_at) as day'),
            DB::raw('SUM(status = "Pending") as pending'),
            DB::raw('SUM(status = "Completed") as completed'),
            DB::raw('SUM(status = "Cancelled") as cancelled')
        )
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get();

        // Pending Orders
        $pendingOrders = Order::with(['farmer', 'fertilizer', 'agrovet'])
            ->where('status', 'Pending')->get();

        // Completed Orders
        $completedOrders = Order::with(['farmer', 'fertilizer', 'agrovet'])
            ->where('status', 'Completed')->get();

        // Cancelled Orders
        $cancelledOrders = Order::with(['farmer', 'fertilizer', 'agrovet'])
            ->where('status', 'Cancelled')->get();

        // Orders by Fertilizer Type
        $orderByFertilizer = Order::select('fertilizer_id', DB::raw('COUNT(*) as total_orders'))
            ->groupBy('fertilizer_id')
            ->with('fertilizer')
            ->get();

        return view('admin.order-reports', compact(
            'orderSummary',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'orderByFertilizer'
        ));
    }
}
