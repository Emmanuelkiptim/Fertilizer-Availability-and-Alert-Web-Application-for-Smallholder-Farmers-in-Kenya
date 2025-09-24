<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    // Show alerts for the logged-in farmer
    public function index()
    {
        $farmer = Auth::user()->farmer;
        $alerts = $farmer ? $farmer->alerts()->latest()->get() : collect();
        return view('farmer.alerts', compact('alerts'));
    }

    // Mark an alert as read
    public function markAsRead($id)
    {
        $alert = Alert::findOrFail($id);
        if ($alert->farmer_id == Auth::user()->farmer->id) {
            $alert->is_read = true;
            $alert->save();
        }
        return redirect()->back();
    }
}
