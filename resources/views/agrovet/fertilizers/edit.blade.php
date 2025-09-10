<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fertilizers') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-lg font-medium mb-6 text-gray-900 dark:text-gray-100">Edit Fertilizer</h2>
                    <form method="POST" action="{{ route('fertilizers.update', $fertilizer->fertilizer_id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="name" class="block text-gray-700 dark:text-gray-200 mb-2">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $fertilizer->name) }}" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                            @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="type" class="block text-gray-700 dark:text-gray-200 mb-2">Type</label>
                            <input type="text" name="type" id="type" value="{{ old('type', $fertilizer->type) }}" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                            @error('type') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-gray-200 mb-2">Current Quantity</label>
                            <input type="number" value="{{ $fertilizer->qty }}" disabled class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="add_qty" class="block text-gray-700 dark:text-gray-200 mb-2">Add Quantity</label>
                            <input type="number" name="add_qty" id="add_qty" value="{{ old('add_qty', 0) }}" min="0" class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Enter the amount to add to the current stock.</div>
                            @error('add_qty') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="price" class="block text-gray-700 dark:text-gray-200 mb-2">Price</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $fertilizer->price) }}" min="0" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                            @error('price') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>Update</x-primary-button>
                            <a href="{{ route('fertilizers.show', $fertilizer->fertilizer_id) }}" class="text-gray-600 dark:text-gray-300 hover:underline">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>