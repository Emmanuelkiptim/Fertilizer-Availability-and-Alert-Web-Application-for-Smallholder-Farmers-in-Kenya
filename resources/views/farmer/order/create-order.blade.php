<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Fertilizer') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <h2>Order Fertilizer</h2>

        <p><strong>Name:</strong> {{ $fertilizer->name }}</p>
        <p><strong>Type:</strong> {{ $fertilizer->type }}</p>
        <p><strong>Price per unit:</strong> KES {{ $fertilizer->price }}</p>
        <p><strong>Agrovet:</strong> {{ $fertilizer->agrovet->shopname }}</p>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="fertilizer_id" value="{{ $fertilizer->fertilizer_id }}">

            <label>Quantity:</label>
            <input type="number" name="quantity" id="quantity" min="1" required>

            <p>Total Amount: KES <span id="total">0</span></p>


            <button type="submit">Confirm Order</button>
        </form>

        <script>
            document.getElementById('quantity').addEventListener('input', function () {
                let qty = this.value;
                let price = {{ $fertilizer->price }};
                document.getElementById('total').innerText = qty * price;
            });
        </script>

    </div>
</x-app-layout>