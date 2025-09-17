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

        // $fertilizers = Fertilizer::where('qty', '>', 0)->get();

        // return view('farmer.fertilizers.fertilizersindex', compact('fertilizers'));
        $farmer = auth()->user()->farmer;

    // Get the farmer’s favourite fertilizers
    $favourites = $farmer->favourites()->get();

    // Get all other fertilizers (not favourites)
    $fertilizers = Fertilizer::where('qty', '>', 0)
        ->whereNotIn('fertilizer_id', $favourites->pluck('fertilizer_id'))
        ->get();

    return view('farmer.fertilizers.fertilizersindex', compact('fertilizers', 'favourites'));
    }
    public function showFertilizer($id){
        $fertilizer = Fertilizer::with('agrovet')->findOrFail($id);
        $farmer = auth()->user()->farmer;
        return view('farmer.fertilizers.fertilizershow', compact('fertilizer'));
    }

}
