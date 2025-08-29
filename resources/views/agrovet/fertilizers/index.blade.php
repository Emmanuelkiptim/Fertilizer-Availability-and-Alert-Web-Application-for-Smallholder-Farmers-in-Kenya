<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fertilizers') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">My Fertilizers</h2>
                        <a href="{{ route('fertilizers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">+ Add New Fertilizer</a>
                    </div>
                    @if(session('success'))
                        <div class="mb-4 text-green-600 text-sm font-semibold">{{ session('success') }}</div>
                    @endif
                    @if($fertilizers->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($fertilizers as $f)
                                        <tr>
                                            <td class="px-4 py-2 font-semibold">{{ $f->name }}</td>
                                            <td class="px-4 py-2">{{ $f->type }}</td>
                                            <td class="px-4 py-2">{{ $f->qty }}</td>
                                            <td class="px-4 py-2">
                                                <a href="{{ route('fertilizers.show', $f->fertilizer_id) }}" class="text-blue-600 hover:underline">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-300">No fertilizers yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
