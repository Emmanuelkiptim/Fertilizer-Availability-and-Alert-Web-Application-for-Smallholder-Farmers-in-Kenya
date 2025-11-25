<?php

namespace App\Http\Controllers;

use App\Models\Fertilizer;
use App\Models\Agrovet;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FertilizerReportController extends Controller
{
    /**
     * Show fertilizer stock summary and purchase trends report.
     */
    public function fertilizerindex()
    {
        // Fertilizer Stock Summary
        $fertilizerStocks = Fertilizer::with('agrovet')
            ->orderBy('name')
            ->paginate(10);

        // For Bar Chart: Fertilizer Type vs Available Quantity
        $typeGroups = Fertilizer::select('type')
            ->groupBy('type')
            ->pluck('type');
        $fertilizerTypes = $typeGroups->toArray();
        $fertilizerQuantities = [];
        foreach ($fertilizerTypes as $type) {
            $fertilizerQuantities[] = Fertilizer::where('type', $type)->sum('qty');
        }

        // Fertilizer Purchase Trends
        // Most purchased fertilizers by season, crop, location
        $mostPurchased = Order::selectRaw('fertilizer_id, SUM(quantity) as total')
            ->groupBy('fertilizer_id')
            ->orderByDesc('total')
            ->with('fertilizer')
            ->limit(10)
            ->get();

        // Top-selling agrovets
        $topAgrovets = Order::selectRaw('agrovet_id, SUM(quantity) as total')
            ->groupBy('agrovet_id')
            ->orderByDesc('total')
            ->with('agrovet')
            ->limit(5)
            ->get();

        // Peak buying periods (by month)
        $ordersByMonth = Order::selectRaw('MONTH(created_at) as month, SUM(quantity) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $months = $ordersByMonth->pluck('month')->map(function($m){ return Carbon::create()->month($m)->format('F'); })->toArray();
        $ordersPerMonth = $ordersByMonth->pluck('total')->toArray();

        // For crops and location, you may need to join with related tables if available
        // Example: $ordersByCrop = ...
        // Example: $ordersByLocation = ...

        return view('admin.fertilizerreport', [
            'fertilizerStocks' => $fertilizerStocks,
            'fertilizerTypes' => $fertilizerTypes,
            'fertilizerQuantities' => $fertilizerQuantities,
            'mostPurchased' => $mostPurchased,
            'topAgrovets' => $topAgrovets,
            'months' => $months,
            'ordersPerMonth' => $ordersPerMonth,
            // 'ordersByCrop' => $ordersByCrop,
            // 'ordersByLocation' => $ordersByLocation,
        ]);
    }
}
