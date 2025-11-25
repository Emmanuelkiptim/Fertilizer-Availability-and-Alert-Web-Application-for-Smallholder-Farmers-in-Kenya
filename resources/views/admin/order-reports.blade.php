
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

<!-- TOTAL REVENUE CARD -->

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h3 class="card-title">Completed Revenue</h3>
            </div>
            <div class="card-body">
                <h2 style="font-weight:bold; color:#256029;">Ksh {{ number_format($totalCompletedRevenue, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title">Cancelled Revenue</h3>
            </div>
            <div class="card-body">
                <h2 style="font-weight:bold; color:#a94442;">Ksh {{ number_format($totalRejectedRevenue, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-dark">
                <h3 class="card-title">Pending Revenue</h3>
            </div>
            <div class="card-body">
                <h2 style="font-weight:bold; color:#856404;">Ksh {{ number_format($totalPendingRevenue, 2) }}</h2>
            </div>
        </div>
    </div>
</div>



<!-- ORDER SUMMARY CHART -->
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Order Summary (Pending, Completed, Cancelled)</h3>
    </div>
    <div class="card-body">
        <!-- Summary Metrics -->
        @php
            $totalOrders = $orderSummary->sum('pending') + $orderSummary->sum('completed') + $orderSummary->sum('rejected');
            $pendingTotal = $orderSummary->sum('pending');
            $completedTotal = $orderSummary->sum('completed');
            $cancelledTotal = $orderSummary->sum('rejected');
            $pendingPct = $totalOrders ? round(($pendingTotal / $totalOrders) * 100, 1) : 0;
            $completedPct = $totalOrders ? round(($completedTotal / $totalOrders) * 100, 1) : 0;
            $cancelledPct = $totalOrders ? round(($cancelledTotal / $totalOrders) * 100, 1) : 0;
            $peakDay = $orderSummary->sortByDesc(function($row){ return $row['pending'] + $row['completed'] + $row['rejected']; })->first();
        @endphp
        <div class="row mb-3 text-center">
            <div class="col-md-3 col-6 mb-2">
                <div class="p-2 rounded bg-success text-white"><strong>Total Orders</strong><br><span style="font-size:1.5rem;">{{ $totalOrders }}</span></div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="p-2 rounded bg-warning text-dark"><strong>Pending</strong><br>{{ $pendingTotal }} ({{ $pendingPct }}%)</div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="p-2 rounded bg-success text-white"><strong>Completed</strong><br>{{ $completedTotal }} ({{ $completedPct }}%)</div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="p-2 rounded bg-danger text-white"><strong>Cancelled</strong><br>{{ $cancelledTotal }} ({{ $cancelledPct }}%)</div>
            </div>
        </div>
        <div class="mb-3 text-center">
            <span class="badge badge-info p-2" style="font-size:1rem;">Peak Order Day: <strong>{{ $peakDay ? $peakDay['day'] : '-' }}</strong></span>
        </div>
        <div style="overflow-x:auto; width:100%;">
            <canvas id="orderSummaryChart" style="width:100%; min-width:900px; max-width:2200px; height:60vh;"></canvas>
        </div>
        <div class="mt-3 text-center">
            <span class="mr-3"><span style="display:inline-block;width:18px;height:18px;background:green;border-radius:3px;margin-right:4px;"></span>Completed</span>
            <span class="mr-3"><span style="display:inline-block;width:18px;height:18px;background:yellow;border-radius:3px;margin-right:4px;border:1px solid #ccc;"></span>Pending</span>
            <span><span style="display:inline-block;width:18px;height:18px;background:red;border-radius:3px;margin-right:4px;"></span>Cancelled</span>
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
            <div class="mt-2">
                {{ $pendingOrders->links('pagination::bootstrap-4') }}
            </div>
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
            <div class="mt-2">
                {{ $completedOrders->links('pagination::bootstrap-4') }}
            </div>
    </div>
</div>

<!-- CANCELLED ORDERS TABLE -->
<!-- REJECTED ORDERS TABLE -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Rejected Orders</h3>
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
                    <th>Rejected Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rejectedOrders as $order)
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
            <div class="mt-2">
                {{ $rejectedOrders->links('pagination::bootstrap-4') }}
            </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ORDER SUMMARY STACKED BAR CHART
    const orderSummaryCtx = document.getElementById('orderSummaryChart').getContext('2d');
    const orderSummaryLabels = @json($orderSummary->pluck('day'));
    const pendingData = @json($orderSummary->pluck('pending'));
    const completedData = @json($orderSummary->pluck('completed'));
    const cancelledData = @json($orderSummary->pluck('rejected'));
    new Chart(orderSummaryCtx, {
        type: 'bar',
        data: {
            labels: orderSummaryLabels,
            datasets: [
                {
                    label: 'Completed',
                    data: completedData,
                    backgroundColor: 'green',
                    stack: 'orders',
                },
                {
                    label: 'Pending',
                    data: pendingData,
                    backgroundColor: 'yellow',
                    stack: 'orders',
                },
                {
                    label: 'Rejected',
                    data: cancelledData,
                    backgroundColor: 'red',
                    stack: 'orders',
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'top' },
                title: { display: false },
                tooltip: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.parsed.y;
                            return `${label}: ${value}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    title: { display: true, text: 'Date' },
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 20,
                        callback: function(value, index, values) {
                            // Show every 2nd or 3rd label for readability
                            return index % 2 === 0 ? this.getLabelForValue(value) : '';
                        }
                    },
                    grid: { display: false }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    title: { display: true, text: 'Number of Orders' },
                }
            }
        }
    });

    // ORDERS BY FERTILIZER TYPE BAR CHART
    const fertilizerCtx = document.getElementById('fertilizerChart').getContext('2d');
    const fertilizerLabels = @json($orderByFertilizerType->pluck('type'));
    const fertilizerData = @json($orderByFertilizerType->pluck('total_orders'));
    // Generate a unique color for each fertilizer type
    function getColor(index, total) {
        // Use HSL for evenly spaced colors
        const hue = Math.round((360 / total) * index);
        return `hsl(${hue}, 65%, 55%)`;
    }
    const fertilizerColors = fertilizerLabels.map((_, i) => getColor(i, fertilizerLabels.length));
    new Chart(fertilizerCtx, {
        type: 'bar',
        data: {
            labels: fertilizerLabels,
            datasets: [{ data: fertilizerData, backgroundColor: fertilizerColors }]
        },
        options: {
            plugins: {
                title: { display: true, text: 'Orders by Fertilizer Type' },
                tooltip: {
                    callbacks: {
                        title: function() {
                            // Show total orders in the tooltip title
                            const total = fertilizerData.reduce((a, b) => a + b, 0);
                            return `Total Orders: ${total}`;
                        },
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            return `${label}`;
                        }
                    }
                }
            }
        }
    });
</script>
@stop