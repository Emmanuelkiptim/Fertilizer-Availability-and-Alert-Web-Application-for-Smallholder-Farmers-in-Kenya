
<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Approved Orders') }}
		</h2>
	</x-slot>

	<div class="p-6">
		<h2>My Approved Orders</h2>

		@if($orders->isEmpty())
			<p>No approved orders found.</p>
		@else
			<div class="table-responsive" style="overflow-x:auto;">
				<table class="min-w-full table-auto">
					<thead>
						<tr>
							<th class="px-4 py-2">Order ID</th>
							<th class="px-4 py-2">Fertilizer Name</th>
							<th class="px-4 py-2">Fertilizer Type</th>
							<th class="px-4 py-2">Quantity</th>
							<th class="px-4 py-2">Total Price</th>
							<th class="px-4 py-2">Agrovet Shop</th>
							<th class="px-4 py-2">Order Date</th>
							<th class="px-4 py-2">Payment Status</th>
						</tr>
					</thead>
					<tbody>
						@foreach($orders as $order)
							<tr>
								<td class="border px-4 py-2 text-center">{{ $order->order_id }}</td>
								<td class="border px-4 py-2 text-center">{{ $order->fertilizer->name }}</td>
								<td class="border px-4 py-2 text-center">{{ $order->fertilizer->type }}</td>
								<td class="border px-4 py-2 text-center">{{ $order->quantity }}</td>
								<td class="border px-4 py-2 text-center">KES {{ $order->total_price }}</td>
								<td class="border px-4 py-2 text-center">{{ $order->agrovet->shopname ?? '-' }}</td>
								<td class="border px-4 py-2 text-center">{{ $order->created_at }}</td>
								<td class="border px-4 py-2 text-center">{{ ucfirst($order->payment_status) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@endif
		<div class="mt-4">
			{{ $orders->links() }}
		</div>

		<div class="mt-8">
			<h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">Approved Orders Graph</h3>
			<canvas id="approvedOrdersChart" height="120"></canvas>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<script>
			// Total quantity price per fertilizer type (all approved orders)
			const allOrders = @json($allOrders);
			const typeTotals = {};
			allOrders.forEach(order => {
				const type = order.fertilizer.type;
				const price = parseFloat(order.total_price);
				typeTotals[type] = (typeTotals[type] || 0) + price;
			});
			const labels = Object.keys(typeTotals);
			const data = Object.values(typeTotals);

			const ctx = document.getElementById('approvedOrdersChart').getContext('2d');
			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: [{
						label: 'Total Quantity Price (KES)',
						data: data,
						backgroundColor: 'rgba(34,197,94,0.5)',
						borderColor: 'rgba(34,197,94,1)',
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		</script>
	</div>
</x-app-layout>
