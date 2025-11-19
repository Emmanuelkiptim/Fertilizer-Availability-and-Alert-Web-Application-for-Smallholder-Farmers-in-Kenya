@extends('adminlte::page')

@section('title', 'Farmers & Agrovets Map Overview')

@section('content_header')
    <h1>Map Overview: Farmers & Agrovets</h1>
@stop

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">All Farmers & Agrovets Locations</h3>
    </div>
    <div class="card-body p-0">
        <div class="w-100" style="min-height:300px;">
            <div id="map" style="height: 400px; width: 100%; min-width: 200px;"></div>
            </div>
            <div class="mt-3 mb-2 d-flex justify-content-center" style="width:100%;">
                <strong class="mr-3">Key:</strong>
                <div class="d-flex align-items-center mr-4">
                    <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png" alt="Green Pin" style="width:20px;height:32px;margin-right:8px;"> Farmer
                </div>
                <div class="d-flex align-items-center">
                    <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png" alt="Red Pin" style="width:20px;height:32px;margin-right:8px;"> Agrovet
                </div>
            </div>
    </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<style>
    @media (max-width: 768px) {
        #map {
            height: 300px !important;
        }
        .card-title {
            font-size: 1.1rem;
        }
        .card-header h3 {
            font-size: 1.2rem;
        }
    }
    @media (max-width: 480px) {
        #map {
            height: 220px !important;
        }
        .card-header h3 {
            font-size: 1rem;
        }
    }
</style>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let map = L.map('map');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        let farmers = @json($farmers);
        let agrovets = @json($agrovets);
        let allCoords = [];
        farmers.forEach(farmer => {
            if (farmer.location_latitude && farmer.location_longitude) {
                allCoords.push([farmer.location_latitude, farmer.location_longitude]);
                L.marker([farmer.location_latitude, farmer.location_longitude], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                        shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                })
                .addTo(map)
                .bindPopup(`<b>Farmer</b><br>${farmer.name}<br>Lat: ${farmer.location_latitude}<br>Lng: ${farmer.location_longitude}`);
            }
        });
        agrovets.forEach(agrovet => {
            if (agrovet.location_latitude && agrovet.location_longitude) {
                allCoords.push([agrovet.location_latitude, agrovet.location_longitude]);
                L.marker([agrovet.location_latitude, agrovet.location_longitude], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                        shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                })
                .addTo(map)
                .bindPopup(`<b>Agrovet</b><br>${agrovet.shopname}<br>Lat: ${agrovet.location_latitude}<br>Lng: ${agrovet.location_longitude}`);
            }
        });
        if (allCoords.length > 0) {
            map.fitBounds(allCoords, {padding: [30, 30]});
        } else {
            map.setView([0.0236, 37.9062], 7);
        }
    });
</script>
@stop
