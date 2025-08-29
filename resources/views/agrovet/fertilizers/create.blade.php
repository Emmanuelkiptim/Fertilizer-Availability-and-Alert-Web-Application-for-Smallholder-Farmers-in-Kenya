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
                    <h2 class="text-lg font-medium mb-6 text-gray-900 dark:text-gray-100">Add Fertilizer</h2>
                    <form method="POST" action="{{ route('fertilizers.store') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-gray-700 dark:text-gray-200 mb-2">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                            @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="type" class="block text-gray-700 dark:text-gray-200 mb-2">Type</label>
                            <input type="text" name="type" id="type" value="{{ old('type') }}" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                            @error('type') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="qty" class="block text-gray-700 dark:text-gray-200 mb-2">Quantity</label>
                            <input type="number" name="qty" id="qty" value="{{ old('qty') }}" min="0" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                            @error('qty') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="price" class="block text-gray-700 dark:text-gray-200 mb-2">Price</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" min="0" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                            @error('price') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="availability" class="block text-gray-700 dark:text-gray-200 mb-2">Availability</label>
                            <select name="availability" id="availability" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                <option value="1" {{ old('availability')==='1' ? 'selected' : '' }}>Available</option>
                                <option value="0" {{ old('availability')==='0' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                            @error('availability') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>Save</x-primary-button>
                            <a href="{{ route('fertilizers.index') }}" class="text-gray-600 dark:text-gray-300 hover:underline">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>