<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminDashboardController;

class UserController extends Controller  
{
     //
     public function Dashboard(){
          if(Auth::check() && Auth::user()->role=='farmer'){
              return view ('farmer.farmer-dashboard'); 
          }
          else if(Auth::check() && Auth::user()->role=='admin'){
              return app(AdminDashboardController::class)->AdminDashboard(); 
          }
          else if(Auth::check() && Auth::user()->role=='agrovet'){
              return view ('agrovet.agrovet-dashboard'); 
          }
          else{
                return redirect('/');
          }
     }

}
