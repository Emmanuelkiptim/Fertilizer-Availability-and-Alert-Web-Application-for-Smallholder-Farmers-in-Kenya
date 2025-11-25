@extends('adminlte::page')

@section('title', 'Fertilizer Stock Summary Report')

@section('content_header')
    <h1>Fertilizer Stock Summary</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Stock Details by Agrovet</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Fertilizer ID</th>
                            <th>Fertilizer Name</th>
                            <th>Current Quantity</th>
                            <th>Unit Price (Ksh)</th>
                            <th>Agrovet Name</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fertilizerStocks as $stock)
                        <tr>
                            <td>{{ $stock->fertilizer_id }}</td>
                            <td>{{ $stock->name }}</td>
                            <td>{{ $stock->qty }}</td>
                            <td>{{ number_format($stock->price, 2) }}</td>
                            <td>{{ $stock->agrovet->shopname ?? 'N/A' }}</td>
                            <td>{{ $stock->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Fertilizer Quantity by Type</h3>
            </div>
            <div class="card-body">
                <canvas id="fertilizerStockChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Most Purchased Fertilizers</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Fertilizer Name</th>
                            <th>Total Purchased</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mostPurchased as $item)
                        <tr>
                            <td>{{ $item->fertilizer->name ?? 'N/A' }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Top-Selling Agrovets</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Agrovet Name</th>
                            <th>Total Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topAgrovets as $agrovet)
                        <tr>
                            <td>{{ $agrovet->agrovet->shopname ?? 'N/A' }}</td>
                            <td>{{ $agrovet->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Peak Buying Periods</h3>
            </div>
            <div class="card-body">
                <canvas id="peakBuyingChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('fertilizerStockChart').getContext('2d');
    const fertilizerTypes = @json($fertilizerTypes);
    const fertilizerQuantities = @json($fertilizerQuantities);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: fertilizerTypes,
            datasets: [{
                label: 'Available Quantity',
                data: fertilizerQuantities,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const ctxPeak = document.getElementById('peakBuyingChart').getContext('2d');
    new Chart(ctxPeak, {
        type: 'bar',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Orders',
                data: @json($ordersPerMonth),
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@stop
