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
