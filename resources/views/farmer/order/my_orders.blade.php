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