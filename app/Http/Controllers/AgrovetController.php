<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agrovet;

class AgrovetController extends Controller
{
    /**
     * Calculate distance between two lat/lng points in kilometers (Haversine formula)
     */
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of the earth in km
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $deltaLat = $lat2 - $lat1;
        $deltaLon = deg2rad($lon2 - $lon1);
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;
        return $distance;
    }
    //
    public function create(){
        return view('agrovet.register');
    }

    public function store(Request $request){
        $request->validate([
            'shopname'=>'required|string',
            'agrovet_phonenumber'=>'required|string',
            'location_latitude'=>'nullable|numeric',
            'location_longitude'=>'nullable|numeric',
        ]);
        $user=Auth::user();

        if ($user->agrovet){
            return redirect ()->route('dashboard')->with('error','You are already registered as an agrovet');

        }
        $agrovet = Agrovet::create([
            'user_id' => $user->id,
            'shopname' => $request->shopname,
            'agrovet_phonenumber' => $request->agrovet_phonenumber,
            'location_latitude' => $request->location_latitude,
            'location_longitude' => $request->location_longitude,
            'name' => $user->name,
        ]);

        // Alert farmers within 5km
        if ($agrovet->location_latitude && $agrovet->location_longitude) {
            $farmers = \App\Models\Farmer::whereNotNull('location_latitude')
                ->whereNotNull('location_longitude')
                ->get();
            foreach ($farmers as $farmer) {
                $distance = $this->haversineDistance(
                    $agrovet->location_latitude,
                    $agrovet->location_longitude,
                    $farmer->location_latitude,
                    $farmer->location_longitude
                );
                if ($distance <= 5) {
                    \App\Models\Alert::create([
                        'farmer_id' => $farmer->id,
                        'message' => 'A new agrovet ('.$agrovet->shopname.') has registered within 5km of your location.',
                        'is_read' => false,
                    ]);
                }
            }
        }
        return redirect()->route('dashboard')->with('Success', 'Agrovet profile created successfully');
    }
    public function update(Request $request){
        $request->validate([
            'shopname'=>'required|string',
            'agrovet_phonenumber'=>'required|string',
            
        ]);
        $user=Auth::user();
        $agrovet=$user->agrovet;
        if(!$agrovet){
            return redirect()->route('dashboard')->with('error','You are not registered as an agrovet');
        }
        $agrovet->update([
            'shopname' => $request->shopname,
            'agrovet_phonenumber' => $request->agrovet_phonenumber,
            'name' => $user->name,
        ]);
        return redirect()->route('dashboard')->with('success','Agrovet profile updated successfully');
    }
    
}
