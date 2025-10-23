<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Fertilizer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class AdminDashboardController extends Controller
{
    //
    public function AdminDashboard()
    {
        if (Auth::check() && Auth::user()->role == 'admin') {
            // Fetch statistics
            $adminCount = User::where('role', 'admin')->count();
            $usersCount = User::count();
            $farmersCount = User::where('role', 'farmer')->count();
            $agrovetsCount = User::where('role', 'agrovet')->count();
            $fertilizersCount = Fertilizer::count();
            $ordersCount = Order::count();
            //new users registration stats daily
            $usersByDay = User::selectRaw('COUNT(id) as total, DATE(created_at) as day')
                ->groupBy('day')
                ->orderBy('day', 'ASC')
                ->pluck('total', 'day');
            // Get top 5 farmers based on engagement (orders + favorites)
            $topFarmers = \DB::table('farmers')
                ->join('users', 'farmers.user_id', '=', 'users.id') // joined with users table
                ->leftJoin('favourites', 'favourites.farmer_id', '=', 'farmers.id')
                ->leftJoin('orders', 'orders.farmer_id', '=', 'farmers.id')
                ->select(
                    'farmers.id',
                    'users.name',
                    DB::raw('COUNT(DISTINCT favourites.id) as favorites_count'),
                    DB::raw('COUNT(DISTINCT orders.order_id) as orders_count')
                )
                ->groupBy('farmers.id', 'users.name')
                ->get()
                ->map(function ($farmer) {
                    $farmer->engagement_score = $farmer->favorites_count + $farmer->orders_count;
                    return $farmer;
                })
                ->sortByDesc('engagement_score')
                ->take(5);
            $farmerNames = $topFarmers->pluck('name');
            $engagementScores = $topFarmers->pluck('engagement_score');
            $orders = $topFarmers->pluck('orders_count');
            $favorites = $topFarmers->pluck('favorites_count');
            $topAgrovets = \DB::table('agrovets')
                ->join('users', 'agrovets.user_id', '=', 'users.id') // joined with users table
                ->leftJoin('fertilizers', 'fertilizers.agrovet_id', '=', 'agrovets.id')
                ->leftJoin('orders', 'orders.agrovet_id', '=', 'agrovets.id')
                ->leftJoin('favourites', 'favourites.fertilizer_id', '=', 'fertilizers.fertilizer_id')
                ->select(
                    'agrovets.id',
                    'users.name',
                    DB::raw('COUNT(DISTINCT orders.order_id) as orders_approved'),
                    DB::raw('COUNT(DISTINCT fertilizers.fertilizer_id) as fertilizers_listed'),
                    DB::raw('COUNT(DISTINCT favourites.id) as favorites_count')
                )
                ->groupBy('agrovets.id', 'users.name')
                ->get()
                ->map(function ($agrovet) {
                    $agrovet->activity_score = $agrovet->orders_approved + $agrovet->fertilizers_listed + $agrovet->favorites_count;
                    return $agrovet;
                })
                ->sortByDesc('activity_score')
                ->take(5);
            $agrovetNames = $topAgrovets->pluck('name');
            $ordersApproved = $topAgrovets->pluck('orders_approved');
            $fertilizersListed = $topAgrovets->pluck('fertilizers_listed');
            $favoritesCount = $topAgrovets->pluck('favorites_count');
            $activityScores = $topAgrovets->pluck('activity_score');

            //pass data to view
            return view('admin.dashboard', compact('adminCount','usersCount', 'farmersCount', 'agrovetsCount', 'fertilizersCount', 'ordersCount', 'usersByDay', 'topFarmers', 'farmerNames', 'engagementScores', 'orders', 'favorites', 'agrovetNames', 'ordersApproved', 'fertilizersListed', 'favoritesCount', 'activityScores'));
        } else {
            return redirect('/');
        }
    }

    public function ordersManagement()
    {
        // Order Summary Report (by day)
        $orderSummary = Order::select(
            DB::raw('DATE(created_at) as day'),
            DB::raw('SUM(status = "Pending") as pending'),
            DB::raw('SUM(status = "Approved") as completed'),
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
            ->where('status', 'Approved')->get();

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
    public function usersManagement()
    {

        $allUsers = \App\Models\User::all();
        $farmers = \App\Models\User::where('role', 'farmer')->get();
        $agrovets = \App\Models\User::where('role', 'agrovet')->get();
        $admins = \App\Models\User::where('role', 'admin')->get();
        return view('admin.users-management', compact('allUsers', 'farmers', 'agrovets', 'admins'));

    }
    // Delete a user
    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.management')->with('success', 'User deleted successfully.');
    }
    // Add a new admin
    public function addAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $admin = new \App\Models\User();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = bcrypt($request->password);
        $admin->role = 'admin';
        $admin->save();
        return redirect()->route('users.management')->with('success', 'Admin registered successfully.');
    }
    public function fertilizerindex()
    {
        // Fertilizer Stock Summary
        $fertilizerStocks = Fertilizer::with('agrovet')
            ->orderBy('name')
            ->get();

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
