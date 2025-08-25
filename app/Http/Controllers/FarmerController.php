<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Farmer;

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

}
