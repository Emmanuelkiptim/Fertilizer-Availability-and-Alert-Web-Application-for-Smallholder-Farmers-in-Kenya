<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Fertilizer;
use App\Models\Farmer;
use App\Models\Agrovet;
use App\Models\Alert;
use App\Http\Controllers\FertilizerController;
use App\Http\Controllers\FarmerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\AgrovetController; 

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

        // Check if requested quantity exceeds available stock
        if ($request->quantity > $fertilizer->qty) {
            return redirect()->back()->withInput()->with('error', 'Your order exceeds the number available in stock.');
        }

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
    public function agrovetOrders(){
        $agrovet = Agrovet::where('user_id', Auth::id())->first();

        if(!$agrovet){
            abort(404, __('Not Authorized.'));
        }

        $orders = Order::where('agrovet_id', $agrovet->id)->with('fertilizer', 'farmer')->latest()->get();
        return view('agrovet.orders.agrovet_orders', compact('orders'));
    }
    public function approveOrder($id)
    {
        $order = Order::findOrFail($id);
        $agrovet = Agrovet::where('user_id', Auth::id())->first();

        if($order->agrovet_id != $agrovet->id){
            abort(404, __('Not Authorized.'));
        }
        //approval of order
        $order->status = 'approved';
        $order->save();
        // Create alert for farmer with fertilizer and agrovet details
        $fertilizer = $order->fertilizer;
        $agrovet = $order->agrovet;
        $agrovetUser = $agrovet ? $agrovet->user : null;
        $orderUrl = route('orders.myOrders');
    $message = 'Your order #' . $order->id . ' for <b>' . ($fertilizer ? $fertilizer->name : 'Fertilizer') . '</b> at <b>' . ($agrovet ? $agrovet->shopname : 'Agrovet') . '</b> (by ' . ($agrovetUser ? $agrovetUser->name : 'Agrovet') . ') has been <b>approved</b>. <a href="' . $orderUrl . '" class="alert-action-btn">View Orders</a>';
        Alert::create([
            'farmer_id' => $order->farmer_id,
            'message' => $message,
        ]);
        //reduce stock
        $fertilizer = $order->fertilizer;
        if($fertilizer->qty>=$order->quantity){
            $fertilizer->qty -= $order->quantity;
            $fertilizer->save();
        }
        // Update availability
        $fertilizer->availability = $fertilizer->qty > 0 ? 'Available' : 'Not Available';

        $fertilizer->save();
        return redirect()->back()->with('success', 'Order approved successfully!');

    }
    
    public function rejectOrder($id)
    {
        $order = Order::findOrFail($id);
        $agrovet = Agrovet::where('user_id', Auth::id())->first();

        if($order->agrovet_id != $agrovet->id){
            abort(404, __('Not Authorized.'));
        }
        //reject order

        $order->status = 'rejected';
        $order->save();
        // Create alert for farmer with fertilizer and agrovet details
        $fertilizer = $order->fertilizer;
        $agrovet = $order->agrovet;
        $agrovetUser = $agrovet ? $agrovet->user : null;
        $orderUrl = route('orders.myOrders');
    $message = 'Your order #' . $order->order_id . ' for <b>' . ($fertilizer ? $fertilizer->name : 'Fertilizer') . '</b> at <b>' . ($agrovet ? $agrovet->shopname : 'Agrovet') . '</b> by ' . ($agrovetUser ? $agrovetUser->name : 'Agrovet') . ' has been <b>rejected</b>. <a href="' . $orderUrl . '" class="alert-action-btn">View Orders</a>';
    Alert::create([
            'farmer_id' => $order->farmer_id,
            'message' => $message,
        ]);

        return redirect()->back()->with('success', 'Order rejected successfully!');
    }

}
