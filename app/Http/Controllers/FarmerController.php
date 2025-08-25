<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function registerFarmer(){
        return view('farmer.EditFarmer');
    }
}
