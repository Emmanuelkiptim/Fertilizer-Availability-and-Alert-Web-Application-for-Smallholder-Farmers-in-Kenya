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
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Fertilizer</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Total Price</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Agrovet Shop</th>
                        <th class="px-4 py-2">Coordinates (longitude, latitude)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="border px-4 py-2">{{ $order->fertilizer->name }}</td>
                            <td class="border px-4 py-2">{{ $order->quantity }}</td>
                            <td class="border px-4 py-2">KES {{ $order->total_price }}</td>
                            <td class="border px-4 py-2">{{ $order->status }}</td>
                            <td class="border px-4 py-2">{{ $order->agrovet->shopname ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $order->agrovet->location_latitude }}, {{ $order->agrovet->location_longitude }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</x-app-layout>