<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-1">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-gray-900 dark:text-gray-100">
                    <h2 class="text-lg font-medium mb-6 text-gray-900 dark:text-gray-100">Orders List</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        </thead>
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal w-20">Order ID</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal w-32">Farmer Name</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal w-32">Fertilizer</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal w-20">Quantity</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal w-24">Total Price</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal w-32">Coordinates</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal w-24">Status</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal w-32">Actions</th>

                                </tr>
                            </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <tr>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->order_id }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->farmer->user->name ?? '-' }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->fertilizer->name ?? '-' }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->quantity }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->total_price }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->farmer->location_latitude }}, {{ $order->farmer->location_longitude }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ ucfirst($order->status) }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">
                                            @if($order->status === 'pending')
                                                <form action="{{ route('orders.approve', $order->order_id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <x-primary-button>Approve</x-primary-button>
                                                </form>
                                                <form action="{{ route('orders.decline', $order->order_id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <x-danger-button>Reject</x-danger-button>
                                                </form>
                                            @else
                                                <span class="text-gray-400">No actions available</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

    
