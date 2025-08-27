<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agrovet;

class AgrovetController extends Controller
{
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
        Agrovet::create([
            'user_id'=>$user->id,
            'shopname'=>$request->shopname,
            'agrovet_phonenumber'=>$request->agrovet_phonenumber,
            'location_latitude'=>$request->location_latitude,
            'location_longitude'=>$request->location_longitude,
        ]);
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
            'shopname'=>$request->shopname,
            'agrovet_phonenumber'=>$request->agrovet_phonenumber,
        ]);
        return redirect()->route('dashboard')->with('success','Agrovet profile updated successfully');
    }
    
}
