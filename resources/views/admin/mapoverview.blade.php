@extends('adminlte::page')

@section('title', 'Farmers & Agrovets Map Overview')

@section('content_header')
    <h1>Map Overview: Farmers & Agrovets</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Farmers & Agrovets Locations</h3>
    </div>
    <div class="card-body">
        <div id="map" style="height: 500px;"></div>
    </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let map = L.map('map'); // Do not set view yet

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Data from controller
        let farmers = @json($farmers);
        let agrovets = @json($agrovets);

        let allCoords = [];

        // Farmer markers (green)
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

        // Agrovet markers (red)
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

        // Fit map to all markers
        if (allCoords.length > 0) {
            map.fitBounds(allCoords, {padding: [30, 30]});
        } else {
            map.setView([0.0236, 37.9062], 7); // fallback to Kenya
        }
    });
</script>
@stop
