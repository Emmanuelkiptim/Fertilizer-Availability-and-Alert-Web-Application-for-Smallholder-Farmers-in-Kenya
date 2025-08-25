<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function Dashboard(){
        if(Auth::check() && Auth::user()->role=='farmer'){
           return view ('farmer.farmer-dashboard'); 
        }
        else if(Auth::check() && Auth::user()->role=='admin'){
           return view ('admin.admin-dashboard'); 
        }
        else if(Auth::check() && Auth::user()->role=='agrovet'){
           return view ('agrovet.agrovetDetails'); 
        }
        else{
            return redirect('/');
        }

    }
}
