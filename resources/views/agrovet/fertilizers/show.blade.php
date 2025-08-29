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
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">{{ $fertilizer->name }}</h2>
                    @if(session('success'))
                        <div class="mb-4 text-green-600 text-sm font-semibold">{{ session('success') }}</div>
                    @endif
                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Type</dt>
                            <dd>{{ $fertilizer->type }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Quantity</dt>
                            <dd>{{ $fertilizer->qty }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Price</dt>
                            <dd>{{ $fertilizer->price }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">Availability</dt>
                            <dd>{{ $fertilizer->availability ? 'Available' : 'Unavailable' }}</dd>
                        </div>
                    </dl>
                    <div class="flex gap-4 mt-6">
                        <a href="{{ route('fertilizers.edit', $fertilizer->fertilizer_id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Edit</a>
                        <a href="{{ route('fertilizers.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition">Back to list</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>