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

@stop