<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fertilizers') }}
        </h2>
    </x-slot>



    <div class="container py-12 flex flex-col items-center justify-center">
        <!-- My Favourite Fertilizers -->
        <div class="mb-8 w-full max-w-4xl">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-lg font-medium mb-6 text-gray-900 dark:text-gray-100 text-center">My Favourite Fertilizers</h2>
                    @if($favourites->isEmpty())
                        <p class="text-gray-600 dark:text-gray-300 text-center">You have no favourite fertilizers yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mx-auto table-auto">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fertilizer</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price (KES)</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Agrovet Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity Available</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Distance (km)</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
@section('css')
    <style>
        @media (max-width: 768px) {
            .container {
                padding: 0.5rem;
            }
            table {
                font-size: 0.9rem;
            }
            th, td {
                padding: 0.3rem 0.5rem !important;
                white-space: nowrap;
            }
            .btn {
                font-size: 0.85rem;
                padding: 0.3rem 0.6rem;
            }
        }
    </style>
@endsection
                                    @foreach($favourites as $fertilizer)
                                        <tr>
                                            <td class="px-4 py-2 font-semibold">{{ $fertilizer->name }}</td>
                                            <td class="px-4 py-2">{{ $fertilizer->type }}</td>
                                            <td class="px-4 py-2"> {{ number_format($fertilizer->price) }} Ksh</td>
                                            <td class="px-4 py-2">{{ $fertilizer->agrovet->user->name ?? 'Unknown' }}</td>
                                            <td class="px-4 py-2">{{ $fertilizer->qty }}</td>
                                            <td class="px-4 py-2">{{ $fertilizer->distance }} km</td>
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
        <!-- Fertilizers in Stock -->
        <div class="w-full max-w-4xl">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-lg font-medium mb-6 text-gray-900 dark:text-gray-100 text-center">Fertilizers in Stock</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fertilizer</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price (KES)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Agrovet Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity Available</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Distance (km)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($fertilizers as $fertilizer)
                                    <tr>
                                        <td class="px-4 py-2 font-semibold">{{ $fertilizer->name }}</td>
                                        <td class="px-4 py-2">{{ $fertilizer->type }}</td>
                                        <td class="px-4 py-2">{{ $fertilizer->price }} Ksh</td>
                                        <td class="px-4 py-2">{{ $fertilizer->agrovet->user->name ?? 'Unknown' }}</td>
                                        <td class="px-4 py-2">{{ $fertilizer->qty }}</td>
                                        <td class="px-4 py-2">{{ $fertilizer->distance }} km</td>
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
                        <div class="row justify-content-center mt-4">
                            <div class="col-auto">
                                {{ $fertilizers->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>