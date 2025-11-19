<div class="mb-4 flex justify-end">
    <form action="{{ route('orders.exportCsv') }}" method="GET">
        <button type="submit" class="btn btn-success px-4 py-2 rounded shadow-sm text-white font-semibold" style="background-color:#28a745; border:none;">
            <i class="fas fa-file-csv mr-2"></i> Download Orders CSV
        </button>
    </form>
</div>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Fertilizer') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <h2>My Orders</h2>

        @if($orders->isEmpty())
            <p>No orders found.</p>
        @else
            <div class="table-responsive" style="overflow-x:auto;">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Order ID</th>
                            <th class="px-4 py-2">Fertilizer Name</th>
                            <th class="px-4 py-2">Fertilizer Type</th>
                            <th class="px-4 py-2">Quantity</th>
                            <th class="px-4 py-2">Total Price</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Agrovet Shop</th>
                            <th class="px-4 py-2">Order Date</th>
                            <th class="px-4 py-2">Payment Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="border px-4 py-2 text-center">{{ $order->order_id }}</td>
                                <td class="border px-4 py-2 text-center">{{ $order->fertilizer->name }}</td>
                                <td class="border px-4 py-2 text-center">{{ $order->fertilizer->type }}</td>
                                <td class="border px-4 py-2 text-center">{{ $order->quantity }}</td>
                                <td class="border px-4 py-2 text-center">KES {{ $order->total_price }}</td>
                                <td class="border px-4 py-2 text-center">{{ $order->status }}</td>
                                <td class="border px-4 py-2 text-center">{{ $order->agrovet->shopname ?? '-' }}</td>
                                <td class="border px-4 py-2 text-center">{{ $order->created_at }}</td>
                                <td class="border px-4 py-2 text-center">
                                    @if($order->status == 'approved' && $order->payment_status == 'not paid')
                                        <a href="{{ route('orders.pay', $order->order_id) }}" class="btn btn-primary">Pay</a>
                                    @elseif($order->payment_status == 'pending')
                                        <span class="text-warning">Payment Pending</span>
                                    @elseif($order->payment_status == 'paid')
                                        <span class="text-success">Paid</span>
                                    @else
                                        <span class="text-muted">Not Paid</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    @if(!$orders->isEmpty())
    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">Order Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('orders.approved') }}" class="bg-green-100 dark:bg-green-900 p-4 rounded shadow block hover:bg-green-200 dark:hover:bg-green-800 transition">
                <div class="text-green-800 dark:text-green-200 font-bold">Approved Orders</div>
                <div class="text-2xl font-semibold mt-1">KES {{ number_format($approvedSum, 2) }}</div>
                <div class="text-sm mt-2 text-green-700 dark:text-green-300 underline">View All Approved Orders</div>
            </a>
            <a href="{{ route('orders.pending') }}" class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded shadow block hover:bg-yellow-200 dark:hover:bg-yellow-800 transition">
                <div class="text-yellow-800 dark:text-yellow-200 font-bold">Pending Orders</div>
                <div class="text-2xl font-semibold mt-1">KES {{ number_format($pendingSum, 2) }}</div>
                <div class="text-sm mt-2 text-yellow-700 dark:text-yellow-300 underline">View All Pending Orders</div>
            </a>
        </div>
    </div>
    @endif
</div>

@section('css')
    <style>
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
            table {
                font-size: 0.9rem;
            }
            th, td {
                padding: 0.3rem 0.5rem !important;
                white-space: nowrap;
            }
        }
    </style>
@endsection
    </div>

</x-app-layout>