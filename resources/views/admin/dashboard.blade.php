@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
    <h1>Admin Dashboard</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-2 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $usersCount }}</h3>
                <p>Total Users</p>
            </div>
            <div class="icon"><i class="fas fa-user"></i></div>
        </div>
    </div>
    <div class="col-lg-2 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $farmersCount }}</h3>
                <p>Total Farmers</p>
            </div>
            <div class="icon"><i class="fas fa-user"></i></div>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $agrovetsCount }}</h3>
                <p>Total Agrovets</p>
            </div>
            <div class="icon"><i class="fas fa-store"></i></div>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $fertilizersCount }}</h3>
                <p>Total Fertilizers</p>
            </div>
            <div class="icon"><i class="fas fa-leaf"></i></div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $ordersCount }}</h3>
                <p>Total Orders</p>
            </div>
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
        </div>
    </div>
</div>
<canvas id="userGrowthChart" height="100"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('userGrowthChart').getContext('2d');

    const userGrowthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($usersByDay->keys()), // days
            datasets: [{
                label: 'User Registrations',
                data: @json($usersByDay->values()), // counts
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
<div class="card">
    <div class="card-header">Top Farmers by Engagement</div>
    <div class="card-body">
        <canvas id="topFarmersChart"></canvas>
    </div>
</div>

<script>
    const ctxFarmers = document.getElementById('topFarmersChart').getContext('2d');
    new Chart(ctxFarmers, {
        type: 'bar',
        data: {
            labels: @json($farmerNames),
            datasets: [{
                label: 'Engagement Score',
                data: @json($engagementScores),
                backgroundColor: 'rgb(40, 167, 69)',
                borderColor: 'black',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let index = context.dataIndex;
                            let orders = @json($orders)[index];
                            let favorites = @json($favorites)[index];
                            let engagement = context.raw;
                            return `Engagement: ${engagement} (Orders: ${orders}, Favorites: ${favorites})`;
                        }
                    }
                }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });
</script>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Top Performing Agrovets</h3>
    </div>
    <div class="card-body">
        <canvas id="agrovetsChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxAgrovets = document.getElementById('agrovetsChart').getContext('2d');
    new Chart(ctxAgrovets, {
        type: 'bar',
        data: {
            labels: @json($agrovetNames),
            datasets: [
                {
                    label: 'Orders Approved',
                    data: @json($ordersApproved),
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                },
                {
                    label: 'Fertilizers Listed',
                    data: @json($fertilizersListed),
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                },
                {
                    label: 'Favorites Received',
                    data: @json($favoritesCount),
                    backgroundColor: 'rgba(153, 102, 255, 0.7)',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        footer: function (items) {
                            let index = items[0].dataIndex;
                            let score = @json($activityScores)[index];
                            return 'Activity Score: ' + score;
                        }
                    }
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>



@stop