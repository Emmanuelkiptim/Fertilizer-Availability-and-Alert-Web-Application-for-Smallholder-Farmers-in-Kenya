<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AccountSettingsController extends Controller
{
    public function loginManagement()
    {
        // Fetch login history and active sessions
        $loginHistory = DB::table('admin_login_histories')->orderByDesc('logged_in_at')->get();
        $sessions = DB::table('sessions')->get();
        return view('admin.account-settings', compact('loginHistory', 'sessions'));
    }

    public function terminateSession($sessionId)
    {
        DB::table('sessions')->where('id', $sessionId)->delete();
        return redirect()->back()->with('success', 'Session terminated successfully.');
    }
}
