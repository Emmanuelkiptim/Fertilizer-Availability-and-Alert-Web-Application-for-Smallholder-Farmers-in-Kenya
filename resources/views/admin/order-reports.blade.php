@extends('adminlte::page')

@section('title', 'Order Reports')

@section('content_header')
<h1>Order Reports</h1>
@stop
@section('content_header')
<div
    style="position:fixed; top:0; left:0; width:100%; background:#fff; z-index:1000; box-shadow:0 2px 8px -2px #ccc; text-align:center; padding:18px 0;">
    <h1 style="font-size:2.5rem; font-weight:bold; margin:0; color:#222; letter-spacing:1px;">Order Reports</h1>
</div>
<div style="height:80px;"></div> <!-- Spacer for fixed header -->
@stop

@section('content')

<!-- ORDER SUMMARY CHART -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Order Summary (Pending, Completed, Cancelled)</h3>
    </div>
    <div class="card-body">
        <div style="overflow-x:auto; width:100%;">
            <canvas id="orderSummaryChart" style="width:100%; min-width:900px; max-width:2200px; height:60vh;"></canvas>
        </div>
    </div>
</div>

<!-- ORDERS BY FERTILIZER TYPE CHART -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Orders by Fertilizer Type</h3>
    </div>
    <div class="card-body">
        <canvas id="fertilizerChart"></canvas>
    </div>
</div>

<!-- PENDING ORDERS TABLE -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pending Orders</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Farmer</th>
                    <th>Agrovet</th>
                    <th>Fertilizer</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingOrders as $order)
                    <tr>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ $order->farmer->user ? $order->farmer->user->name : $order->farmer->name }}</td>
                        <td>{{ $order->agrovet->user ? $order->agrovet->user->name : $order->agrovet->name }}</td>
                        <td>{{ $order->fertilizer->name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->total_price }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- COMPLETED ORDERS TABLE -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Completed Orders</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Farmer</th>
                    <th>Agrovet</th>
                    <th>Fertilizer</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Completed Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($completedOrders as $order)
                    <tr>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ $order->farmer->user ? $order->farmer->user->name : $order->farmer->name }}</td>
                        <td>{{ $order->agrovet->user ? $order->agrovet->user->name : $order->agrovet->name }}</td>
                        <td>{{ $order->fertilizer->name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->total_price }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- CANCELLED ORDERS TABLE -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cancelled Orders</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Farmer</th>
                    <th>Agrovet</th>
                    <th>Fertilizer</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Cancelled Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cancelledOrders as $order)
                    <tr>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ $order->farmer->user ? $order->farmer->user->name : $order->farmer->name }}</td>
                        <td>{{ $order->agrovet->user ? $order->agrovet->user->name : $order->agrovet->name }}</td>
                        <td>{{ $order->fertilizer->name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->total_price }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ORDER SUMMARY BAR CHART
    const orderSummaryCtx = document.getElementById('orderSummaryChart').getContext('2d');
    const orderSummaryChart = new Chart(orderSummaryCtx, {
        type: 'bar',
        data: {
            labels: @json($orderSummary->pluck('day')),
            datasets: [
                { label: 'Pending', data: @json($orderSummary->pluck('pending')), backgroundColor: 'yellow' },
                { label: 'Approved', data: @json($orderSummary->pluck('completed')), backgroundColor: 'green' },
                { label: 'Cancelled', data: @json($orderSummary->pluck('cancelled')), backgroundColor: 'red' }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: { display: false }, // Title handled by sticky div
                legend: { display: false } // Legend handled by sticky div
            },
            scales: {
                x: {
                    min: 0,
                    max: 6,
                    title: { display: true, text: 'Date' },
                    ticks: { autoSkip: false },
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Number of Orders' },
                    position: 'left',
                    offset: false
                }
            },
            pan: {
                enabled: true,
                mode: 'x',
            },
            zoom: {
                enabled: true,
                mode: 'x',
            }
        }
    });

    // ORDERS BY FERTILIZER PIE CHART
    const fertilizerCtx = document.getElementById('fertilizerChart').getContext('2d');
    new Chart(fertilizerCtx, {
        type: 'pie',
        data: {
            labels: @json($orderByFertilizer->map(fn($o) => $o->fertilizer->type)),
            datasets: [{ data: @json($orderByFertilizer->pluck('total_orders')), backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'] }]
        },
        options: {
            plugins: {
                title: { display: true, text: 'Orders by Fertilizer Type' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const data = context.chart.data.datasets[0].data;
                            const total = data.reduce((a, b) => a + b, 0);
                            const percentage = total ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
</script>
@stop