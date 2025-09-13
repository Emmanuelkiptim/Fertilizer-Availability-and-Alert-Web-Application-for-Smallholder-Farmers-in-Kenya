<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fertilizers') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Fertilizer Details</h2>
                    <dl class="divide-y divide-gray-200 dark:divide-gray-700 mb-6">
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Name</dt>
                            <dd>{{ $fertilizer->name }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Type</dt>
                            <dd>{{ $fertilizer->type }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Price</dt>
                            <dd>KES {{ $fertilizer->price }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Quantity Available</dt>
                            <dd>{{ $fertilizer->qty }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Availability</dt>
                            <dd>{{ $fertilizer->availability ? 'Available' : 'Unavailable' }}</dd>
                        </div>
                    </dl>
                    <h3 class="text-lg font-semibold mt-8 mb-4 text-gray-900 dark:text-gray-100">Agrovet Information</h3>
                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Name</dt>
                            <dd>{{ $fertilizer->agrovet->user->name ?? '-' }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Email</dt>
                            <dd>{{ $fertilizer->agrovet->user->email ?? '-' }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Phone</dt>
                            <dd>{{ $fertilizer->agrovet->agrovet_phonenumber }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Coordinates</dt>
                            <dd>{{ $fertilizer->agrovet->location_latitude }}, {{ $fertilizer->agrovet->location_longitude }}</dd>
                        </div>
                    </dl>
                    <div class="mt-8 flex gap-4">
                        <a href="{{ route('orders.create', ['fertilizer_id' => $fertilizer->fertilizer_id]) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Order This Fertilizer</a>
                        <a href="{{ route('farmers.fertilizers.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition">⬅ Back to Fertilizers List</a>
                        <form action="{{ route('favourites.toggle', $fertilizer->fertilizer_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                ⭐ {{ Auth::user()->farmer->favourites->contains($fertilizer->fertilizer_id) ? 'Remove from Favourites' : 'Add to Favourites' }}
                        </button>
                        </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>