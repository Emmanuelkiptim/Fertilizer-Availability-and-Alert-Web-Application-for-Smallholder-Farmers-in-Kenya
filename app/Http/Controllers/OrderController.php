<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Fertilizer;
use App\Models\Farmer;
use App\Http\Controllers\FertilizerController;
use App\Http\Controllers\FarmerController;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //
    public function create($id)
    {
        $fertilizer = Fertilizer::with('agrovet')->findOrFail($id);
        return view('farmer.order.create-order', compact('fertilizer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fertilizer_id' => 'required|exists:fertilizers,fertilizer_id',
            'quantity' => 'required|integer|min:1',
        ]);


        $fertilizer = Fertilizer::findOrFail($request->fertilizer_id);
    $farmer = \App\Models\Farmer::where('user_id', auth()->id())->firstOrFail();
    $farmer_id = $farmer->id;
        $agrovet_id = $fertilizer->agrovet_id;

        $total = $fertilizer->price * $request->quantity;

        Order::create([
            'farmer_id' => $farmer_id,
            'agrovet_id' => $agrovet_id,
            'fertilizer_id' => $fertilizer->fertilizer_id,
            'quantity' => $request->quantity,
            'total_price' => $total,
            'status' => 'pending'
        ]);

    return redirect()->route('orders.myOrders')->with('success', 'Order placed successfully!');
    }
    public function myOrders()
        {
            $farmer = Farmer::where('user_id', Auth::id())->first();

            $orders = Order::where('farmer_id', $farmer->id)->with('fertilizer', 'agrovet')->get();

            return view('farmer.order.my_orders', compact('orders'));
        }
}
