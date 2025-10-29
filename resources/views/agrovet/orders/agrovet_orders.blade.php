<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-gray-900 dark:text-gray-100">
                    <h2 class="text-lg font-medium mb-6 text-gray-900 dark:text-gray-100">Orders List</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal">Order ID</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal">Farmer Name</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal">Fertilizer</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal">Fertilizer Type</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal">Quantity</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal">Total Price</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal">Status</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal">Payment Status</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->order_id }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->farmer->user->name ?? '-' }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->fertilizer->name ?? '-' }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->fertilizer->type ?? '-' }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->quantity }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ $order->total_price }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">{{ ucfirst($order->status) }}</td>
                                        <td class="px-3 py-4 whitespace-normal text-sm text-gray-900">
                                            @if($order->payment_status == 'paid')
                                                <span class="text-success">Paid</span>
                                            @elseif($order->payment_status == 'pending')
                                                <span class="text-warning">Payment Pending</span>
                                            @else
                                                <span class="text-muted">Not Paid</span>
                                            @endif
                                        </td>
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
                    <!-- Order Summary Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">Order Summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-green-100 dark:bg-green-900 p-4 rounded shadow">
                                <div class="text-green-800 dark:text-green-200 font-bold">Approved Orders</div>
                                <div class="text-2xl font-semibold mt-1">Ksh {{ number_format($approvedSum, 2) }}</div>
                            </div>
                            <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded shadow">
                                <div class="text-yellow-800 dark:text-yellow-200 font-bold">Pending Orders</div>
                                <div class="text-2xl font-semibold mt-1">Ksh {{ number_format($pendingSum, 2) }}</div>
                            </div>
                            <div class="bg-red-100 dark:bg-red-900 p-4 rounded shadow">
                                <div class="text-red-800 dark:text-red-200 font-bold">Rejected Orders</div>
                                <div class="text-2xl font-semibold mt-1">Ksh {{ number_format($rejectedSum, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


