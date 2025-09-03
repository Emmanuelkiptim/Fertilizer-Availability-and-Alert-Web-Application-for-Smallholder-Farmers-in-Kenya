<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Fertilizer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                @if(session('error'))
                    <div id="errorModal" class="fixed inset-0 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full border border-red-400">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                                <span class="text-red-600 font-semibold">Error</span>
                            </div>
                            <p class="text-gray-800 mb-4">{{ session('error') }}</p>
                        </div>
                        <div class="fixed inset-0 bg-black opacity-30"></div>
                    </div>
                    <script>
                        setTimeout(function() {
                            var pop_up = document.getElementById('errorModal');
                            if(pop_up) pop_up.style.display = 'none';
                        }, 3000);
                    </script>
                @endif
                <h2 class="text-lg font-medium mb-6 text-gray-900 dark:text-gray-100">Order Fertilizer</h2>
                <div class="mb-4">
                    <div class="mb-2"><span class="font-semibold">Name:</span> {{ $fertilizer->name }}</div>
                    <div class="mb-2"><span class="font-semibold">Type:</span> {{ $fertilizer->type }}</div>
                    <div class="mb-2"><span class="font-semibold">Available Quantity:</span> {{ $fertilizer->qty }}</div>
                    <div class="mb-2"><span class="font-semibold">Price per unit:</span> KES {{ $fertilizer->price }}</div>
                    <div class="mb-2"><span class="font-semibold">Agrovet Shop Name:</span> {{ $fertilizer->agrovet->shopname }}</div>
                </div>
                <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="fertilizer_id" value="{{ $fertilizer->fertilizer_id }}">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <span class="font-semibold">Total Amount:</span> KES <span id="total">0</span>
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Confirm Order</button>
                </form>
                <script>
                    document.getElementById('quantity').addEventListener('input', function () {
                        let qty = this.value;
                        let price = {{ $fertilizer->price }};
                        document.getElementById('total').innerText = qty * price;
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>