<?php

namespace App\Http\Controllers;

use App\Models\Fertilizer;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function toggle(Fertilizer $fertilizer)
    {
        $farmer = Auth::user()->farmer;

        if (! $farmer) {
            return back()->with('error', 'Farmer profile not found.');
        }

        // IMPORTANT: use fertilizer_id, not id
        $fertilizerId = $fertilizer->fertilizer_id;

        if ($farmer->favourites()->where('favourites.fertilizer_id', $fertilizerId)->exists()) {
            // remove favourite
            $farmer->favourites()->detach($fertilizerId);
            return back()->with('success', 'Fertilizer removed from favourites.');
        } else {
            // add favourite
            $farmer->favourites()->attach($fertilizerId);
            return back()->with('success', 'Fertilizer added to favourites.');
        }
    }

    public function index()
    {
        $favourites = collect();

        // If user logged in AND has a farmer profile, load favourites
        if (Auth::check() && Auth::user()->farmer) {
            // Use the correct primary key (fertilizer_id) if your model uses a custom PK
            $farmer = Auth::user()->farmer;
            $favourites = $farmer->favourites()->with('agrovet')->get();
        }

        return view('farmer.fertilizers.fertilizersindex', compact('favourites'));
    }
}
