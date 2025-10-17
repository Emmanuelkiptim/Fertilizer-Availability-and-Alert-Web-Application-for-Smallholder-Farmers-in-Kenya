<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pending Orders') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <h2>Pending Orders</h2>

        @if($orders->isEmpty())
            <p>No pending orders found.</p>
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
                            <th class="px-4 py-2">Actions</th>
                            <th class="px-4 py-2">Call agrovet</th>
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
                                    <form action="{{ route('orders.cancel', $order->order_id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel</button>
                                    </form>
                                </td>
                                <td class="border px-4 py-2 text-center"><a href="tel:{{ $order->agrovet->phone }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-xs">Call</a></td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-8">
                <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded shadow">
                    <div class="text-yellow-800 dark:text-yellow-200 font-bold">Total Pending Orders Value</div>
                    <div class="text-2xl font-semibold mt-1">KES {{ number_format($pendingSum, 2) }}</div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>