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
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

use Illuminate\Support\Facades\Auth;

class OrderController extends Controller{
    // ...existing code...

    public function approvedOrders()
    {
        $farmer = Farmer::where('user_id', Auth::id())->first();
        $ordersPaginated = Order::where('farmer_id', $farmer->id)
            ->where('status', 'approved')
            ->with('fertilizer', 'agrovet')
            ->paginate(5);
        $allOrders = Order::where('farmer_id', $farmer->id)
            ->where('status', 'approved')
            ->with('fertilizer', 'agrovet')
            ->get();
        return view('farmer.order.approved_orders', [
            'orders' => $ordersPaginated,
            'allOrders' => $allOrders
        ]);
    }

    // Stripe: Create Checkout session for an order
    public function createStripeSession($orderId)
    {
        $order = Order::findOrFail($orderId);
        if ($order->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Order already paid.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'kes',
                    'product_data' => [
                        'name' => 'Order #' . $order->order_id,
                        'description' => 'Fertilizer order',
                    ],
                    'unit_amount' => (int)($order->total_price * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('orders.paymentSuccess', ['orderId' => $order->order_id]),
            'cancel_url' => route('orders.myOrders'),
        ]);

        $order->stripe_session_id = $session->id;
        $order->payment_status = 'pending';
        $order->save();

        return redirect($session->url);
    }

    // Stripe: Webhook to handle payment confirmation
    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->server('HTTP_STRIPE_SIGNATURE');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\Exception $e) {
            return response('Webhook Error: ' . $e->getMessage(), 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $order = Order::where('stripe_session_id', $session->id)->first();
            if ($order) {
                $order->payment_status = 'paid';
                $order->save();
            }
        }

        return response('Webhook handled', 200);
    }

    // ...existing code...
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

        $approvedSum = Order::where('farmer_id', $farmer->id)->where('status', 'approved')->sum('total_price');
        $pendingSum = Order::where('farmer_id', $farmer->id)->where('status', 'pending')->sum('total_price');

        return view('farmer.order.my_orders', compact('orders', 'approvedSum', 'pendingSum'));
    }

    public function pendingOrders()
    {
        $farmer = Farmer::where('user_id', Auth::id())->first();
        $orders = Order::where('farmer_id', $farmer->id)
            ->where('status', 'pending')
            ->with('fertilizer', 'agrovet')
            ->get();
        $pendingSum = $orders->sum('total_price');
        return view('farmer.order.pending_orders', compact('orders', 'pendingSum'));
    }
    public function agrovetOrders(){
        $agrovet = Agrovet::where('user_id', Auth::id())->first();

        
        $orders = Order::where('agrovet_id', $agrovet->id)->with('fertilizer', 'farmer')->latest()->get();

        $approvedSum = Order::where('agrovet_id', $agrovet->id)->where('status', 'approved')->sum('total_price');
        $rejectedSum = Order::where('agrovet_id', $agrovet->id)->where('status', 'rejected')->sum('total_price');
        $pendingSum = Order::where('agrovet_id', $agrovet->id)->where('status', 'pending')->sum('total_price');

        return view('agrovet.orders.agrovet_orders', compact('orders', 'approvedSum', 'rejectedSum', 'pendingSum'));
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
    $message = 'Your order #' . $order->order_id . ' for <b>' . ($fertilizer ? $fertilizer->name : 'Fertilizer') . '</b> at <b>' . ($agrovet ? $agrovet->shopname : 'Agrovet') . '</b> (by ' . ($agrovetUser ? $agrovetUser->name : 'Agrovet') . ') has been <b>approved</b>. <a href="' . $orderUrl . '" class="alert-action-btn">View Orders</a>';
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
    public function cancelOrder($orderId)
    {
        $order = Order::where('order_id', $orderId)
            ->where('farmer_id', Auth::user()->farmer->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $order->status = 'cancelled';
        $order->save();

        // Optionally, create an alert for the agrovet or farmer
        // Alert::create([...]);

        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }
    /**
     * Export farmer's orders as CSV
     */
    public function exportCsv()
    {
        $farmer = \App\Models\Farmer::where('user_id', auth()->id())->firstOrFail();
        $orders = \App\Models\Order::where('farmer_id', $farmer->id)
            ->with(['fertilizer', 'agrovet'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="my_orders.csv"',
        ];

        $columns = ['Order ID', 'Fertilizer', 'Type', 'Agrovet', 'Quantity', 'Total Price', 'Status', 'Order Date'];

        $callback = function() use ($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_id,
                    $order->fertilizer->name ?? '',
                    $order->fertilizer->type ?? '',
                    $order->agrovet->shopname ?? '',
                    $order->quantity,
                    $order->total_price,
                    ucfirst($order->status),
                    $order->created_at,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
