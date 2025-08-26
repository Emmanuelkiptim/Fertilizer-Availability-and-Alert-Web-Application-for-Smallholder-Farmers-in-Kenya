<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Farmer Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're can complete registration here!") }}
                    <form action="{{ route('farmer.store') }}" method="POST" class="max-w-md mx-auto mt-8 bg-white dark:bg-gray-800 p-6 rounded shadow">
                    @csrf
                        <div class="mb-4">
                            <label for="farmer_phonenumber" class="block text-gray-700 dark:text-gray-200 mb-2">Farmer Phone Number: </label>
                            <input type="text" name="farmer_phonenumber" class="w-full p-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="location_latitude" class="block text-gray-700 dark:text-gray-200 mb-2">Location Latitude</label>
                            <input type="text" name="location_latitude" class="w-full p-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="location_longitude" class="block text-gray-700 dark:text-gray-200 mb-2">Location Longitude</label>
                            <input type="text" name="location_longitude" class="w-full p-2 border rounded" required>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
