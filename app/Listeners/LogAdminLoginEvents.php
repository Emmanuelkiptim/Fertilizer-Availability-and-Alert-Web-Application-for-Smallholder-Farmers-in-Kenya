<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Request;
use App\Models\AdminLoginHistory;

class LogAdminLoginEvents
{
    public function handleLogin(Login $event)
    {
        $user = $event->user;
        if ($user->role === 'admin') {
            AdminLoginHistory::create([
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'ip_address' => Request::ip(),
                'device' => Request::header('User-Agent'),
                'status' => 'success',
                'logged_in_at' => now(),
            ]);
        }
    }

    public function handleLogout(Logout $event)
    {
        $user = $event->user;
        if ($user && $user->role === 'admin') {
            $lastLogin = AdminLoginHistory::where('user_id', $user->id)
                ->where('session_id', session()->getId())
                ->orderByDesc('logged_in_at')
                ->first();
            if ($lastLogin && !$lastLogin->logged_out_at) {
                $lastLogin->update(['logged_out_at' => now()]);
            }
        }
    }
}
