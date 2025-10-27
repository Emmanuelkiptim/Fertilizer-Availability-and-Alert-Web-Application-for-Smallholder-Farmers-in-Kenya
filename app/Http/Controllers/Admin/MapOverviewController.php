<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Agrovet;

class MapOverviewController extends Controller
{
    public function index()
    {
        $farmers = Farmer::with('user')
            ->get()
            ->map(function ($farmer) {
                return [
                    'name' => $farmer->user->name ?? 'Unknown',
                    'location_latitude' => $farmer->location_latitude,
                    'location_longitude' => $farmer->location_longitude,
                ];
            });

        $agrovets = Agrovet::get()
            ->map(function ($agrovet) {
                return [
                    'shopname' => $agrovet->shopname,
                    'location_latitude' => $agrovet->location_latitude,
                    'location_longitude' => $agrovet->location_longitude,
                ];
            });

        return view('admin.mapoverview', compact('farmers', 'agrovets'));
    }
}

