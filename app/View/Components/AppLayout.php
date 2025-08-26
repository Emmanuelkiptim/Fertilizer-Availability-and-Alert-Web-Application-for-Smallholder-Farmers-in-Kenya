<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    { 
        if(Auth::check() && Auth::user()->role=='farmer'){
                return view ('farmer.layouts.app'); 
                }
                else if(Auth::check() && Auth::user()->role=='admin'){
                return view ('admin.layouts.app'); 
                }
                else if(Auth::check() && Auth::user()->role=='agrovet'){
                return view ('agrovet.layouts.app'); 
                }
                else{
                    return ('404 error');
                }
        
    }
}
