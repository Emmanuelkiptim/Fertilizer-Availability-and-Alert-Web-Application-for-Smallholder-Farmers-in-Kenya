<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fertilizers') }}
        </h2>
    </x-slot>
    <div class="container ">
        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-lg font-medium mb-6 text-gray-900 dark:text-gray-100">My Favourite Fertilizers</h2>

                        @if($favourites->isEmpty())
                            <p class="text-gray-600 dark:text-gray-300 text-center">You have no favourite fertilizers yet.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mx-auto">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fertilizer</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Qty Available</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Agrovet</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($favourites as $fertilizer)
                                            <tr>
                                                <td class="px-4 py-2 font-semibold">{{ $fertilizer->name }}</td>
                                                <td class="px-4 py-2">{{ $fertilizer->type }}</td>
                                                <td class="px-4 py-2">Ksh {{ number_format($fertilizer->price) }}</td>
                                                <td class="px-4 py-2">{{ $fertilizer->qty }}</td>
                                                <td class="px-4 py-2">
                                                    {{ $fertilizer->agrovet->user->name ?? 'Unknown' }}<br>
                                                    <small>{{ $fertilizer->agrovet->phone ?? '' }}</small>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <a href="{{ route('farmers.fertilizers.show', $fertilizer->fertilizer_id) }}" 
                                                    class="text-blue-500 hover:underline">View Details</a>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <form action="{{ route('favourites.toggle', $fertilizer->fertilizer_id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-lg font-medium mb-6 text-gray-900 dark:text-gray-100">Fertilizers in Stock</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price (KES)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Availability</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($fertilizers as $fertilizer)
                                    <tr>
                                        <td class="px-4 py-2 font-semibold">{{ $fertilizer->name }}</td>
                                        <td class="px-4 py-2">{{ $fertilizer->type }}</td>
                                        <td class="px-4 py-2">{{ $fertilizer->qty }}</td>
                                        <td class="px-4 py-2">{{ $fertilizer->price }}</td>
                                        <td class="px-4 py-2">{{ $fertilizer->availability ? 'Available' : 'Unavailable' }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('farmers.fertilizers.show', $fertilizer->fertilizer_id) }}" 
                                            class="text-blue-500 hover:underline">View Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 text-center text-gray-600 dark:text-gray-300">No fertilizers in stock right now.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
    
</x-app-layout>