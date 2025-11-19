<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Farmer;
use App\Models\Fertilizer;


class FarmerController extends Controller
{
    public function create(){
        return view('farmer.register');
    }

    public function store(Request $request){
        $request->validate([
            'farmer_phonenumber'=>'required|string',
            'location_latitude'=>'nullable|numeric',
            'location_longitude'=>'nullable|numeric',
        ]);
        $user=Auth::user();

        if ($user->farmer){
            return redirect ()->route('dashboard')->with('error','You are already registered as a farmer');

        }
        Farmer::create([
            'user_id'=>$user->id,
            'farmer_phonenumber'=>$request->farmer_phonenumber,
            'location_latitude'=>$request->location_latitude,
            'location_longitude'=>$request->location_longitude,
        ]);
        return redirect()->route('dashboard')->with('Success', 'Farmer profile created successfully');
    }

    public function update(Request $request)
    {
        $request->validate([
            'farmer_phonenumber' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$user->farmer) {
            return redirect()->route('dashboard')->with('error', 'You are not registered as a farmer');
        }

        $user->farmer->update([
            'farmer_phonenumber' => $request->farmer_phonenumber,
            
        ]);

        return redirect()->route('dashboard')->with('success', 'Farmer information updated successfully');
    }
    public function listFertilizers(){
        $farmer = auth()->user()->farmer;
        $farmerLat = $farmer->location_latitude;
        $farmerLng = $farmer->location_longitude;

        $favourites = $farmer->favourites()->get();
        $fertilizers = \App\Models\Fertilizer::where('qty', '>', 0)
            ->whereNotIn('fertilizer_id', $favourites->pluck('fertilizer_id'))
            ->paginate(15);

        // Add distance to each fertilizer
        foreach ($fertilizers as $fertilizer) {
            $agrovetLat = $fertilizer->agrovet->location_latitude ?? null;
            $agrovetLng = $fertilizer->agrovet->location_longitude ?? null;
            if ($farmerLat && $farmerLng && $agrovetLat && $agrovetLng) {
                $distance = $this->getDistanceFromORS($farmerLng, $farmerLat, $agrovetLng, $agrovetLat);
                $fertilizer->distance = $distance;
            } else {
                $fertilizer->distance = null;
            }
        }
        foreach ($favourites as $fertilizer) {
            $agrovetLat = $fertilizer->agrovet->location_latitude ?? null;
            $agrovetLng = $fertilizer->agrovet->location_longitude ?? null;
            if ($farmerLat && $farmerLng && $agrovetLat && $agrovetLng) {
                $distance = $this->getDistanceFromORS($farmerLng, $farmerLat, $agrovetLng, $agrovetLat);
                $fertilizer->distance = $distance;
            } else {
                $fertilizer->distance = null;
            }
        }
        return view('farmer.fertilizers.fertilizersindex', compact('fertilizers', 'favourites'));
    }

    // Helper to get distance from OpenRouteService
    private function getDistanceFromORS($farmerLng, $farmerLat, $agrovetLng, $agrovetLat) {
        try {
            $client = new \GuzzleHttp\Client();
            $apiKey = 'eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6IjM1MjljMDRmOGQ4OTQwYTM5M2IxMzk2NzBjOTQyMzc0IiwiaCI6Im11cm11cjY0In0='; // Replace with your key
            $response = $client->post('https://api.openrouteservice.org/v2/directions/driving-car', [
                'headers' => [
                    'Authorization' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'coordinates' => [
                        [$farmerLng, $farmerLat],
                        [$agrovetLng, $agrovetLat]
                    ]
                ])
            ]);
            $data = json_decode($response->getBody(), true);
            if (isset($data['routes'][0]['summary']['distance'])) {
                // Convert meters to km, round to 2 decimals
                return round($data['routes'][0]['summary']['distance'] / 1000, 2);
            }
        } catch (\Exception $e) {
            // Log error or ignore
        }
        return null;
    }
    public function showFertilizer($id){
        $fertilizer = Fertilizer::with('agrovet')->findOrFail($id);
        $farmer = auth()->user()->farmer;
        return view('farmer.fertilizers.fertilizershow', compact('fertilizer'));
    }

}
