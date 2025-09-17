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
            $topAgrovets=\DB::table('agrovets')
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
        return view('admin.dashboard', compact('usersCount', 'farmersCount', 'agrovetsCount', 'fertilizersCount', 'ordersCount', 'usersByDay', 'topFarmers', 'farmerNames', 'engagementScores', 'orders', 'favorites', 'agrovetNames', 'ordersApproved', 'fertilizersListed', 'favoritesCount', 'activityScores'));
    }
    else {
        return redirect('/');
    }
}
public function usersManagement() {

    $allUsers = \App\Models\User::all();
    $farmers = \App\Models\User::where('role', 'farmer')->get();
    $agrovets = \App\Models\User::where('role', 'agrovet')->get();
    $admins = \App\Models\User::where('role', 'admin')->get();
    return view('admin.users-management', compact('allUsers', 'farmers', 'agrovets', 'admins'));

}
// Delete a user
    public function destroy($id) {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.management')->with('success', 'User deleted successfully.');
    }
    // Add a new admin
    public function addAdmin(Request $request) {
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
}
