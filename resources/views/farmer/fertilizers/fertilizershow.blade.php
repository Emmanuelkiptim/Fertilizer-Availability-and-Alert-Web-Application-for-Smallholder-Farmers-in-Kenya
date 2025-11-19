<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fertilizers') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col lg:flex-row gap-8">
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Fertilizer Details</h2>
                            <dl class="divide-y divide-gray-200 dark:divide-gray-700 mb-6">
                                <div class="py-2 flex justify-between">
                                    <dt class="font-medium text-gray-700 dark:text-gray-300">Name</dt>
                                    <dd>{{ $fertilizer->name }}</dd>
                                </div>
                                <div class="py-2 flex justify-between">
                                    <dt class="font-medium text-gray-700 dark:text-gray-300">Type</dt>
                                    <dd>{{ $fertilizer->type }}</dd>
                                </div>
                                <div class="py-2 flex justify-between">
                                    <dt class="font-medium text-gray-700 dark:text-gray-300">Price</dt>
                                    <dd>KES {{ $fertilizer->price }}</dd>
                                </div>
                                <div class="py-2 flex justify-between">
                                    <dt class="font-medium text-gray-700 dark:text-gray-300">Quantity Available</dt>
                                    <dd>{{ $fertilizer->qty }}</dd>
                                </div>
                                <div class="py-2 flex justify-between">
                                    <dt class="font-medium text-gray-700 dark:text-gray-300">Availability</dt>
                                    <dd>{{ $fertilizer->availability ? 'Available' : 'Unavailable' }}</dd>
                                </div>
                            </dl>
                            <h3 class="text-lg font-semibold mt-8 mb-4 text-gray-900 dark:text-gray-100">Agrovet Information</h3>
                            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="py-2 flex justify-between">
                                    <dt class="font-medium text-gray-700 dark:text-gray-300">Name</dt>
                                    <dd>{{ $fertilizer->agrovet->user->name ?? '-' }}</dd>
                                </div>
                                <div class="py-2 flex justify-between">
                                    <dt class="font-medium text-gray-700 dark:text-gray-300">Email</dt>
                                    <dd>{{ $fertilizer->agrovet->user->email ?? '-' }}</dd>
                                </div>
                                <div class="py-2 flex justify-between">
                                    <dt class="font-medium text-gray-700 dark:text-gray-300">Phone</dt>
                                    <dd>{{ $fertilizer->agrovet->agrovet_phonenumber }}</dd>
                                </div>
                                <div class="py-2 flex justify-between">
                                    <dt class="font-medium text-gray-700 dark:text-gray-300">Agrovet Coordinates</dt>
                                    <dd>{{ $fertilizer->agrovet->location_latitude }}, {{ $fertilizer->agrovet->location_longitude }}</dd>
                                </div>
                            </dl>
                            <div class="mt-8 flex gap-4 flex-wrap">
                                <a href="{{ route('orders.create', ['fertilizer_id' => $fertilizer->fertilizer_id]) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Order This Fertilizer</a>
                                <a href="{{ route('farmers.fertilizers.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition">⬅ Back to Fertilizers List</a>
                                <form action="{{ route('favourites.toggle', $fertilizer->fertilizer_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">
                                        ⭐ {{ Auth::user()->farmer->favourites->contains($fertilizer->fertilizer_id) ? 'Remove from Favourites' : 'Add to Favourites' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold mt-8 mb-4 text-gray-900 dark:text-gray-100">Map</h3>
                            <div id="map" style="height: 400px;"></div>
                            <div id="output" style="margin-top: 15px; font-weight: bold;"></div>
                        </div>
                    </div>
                    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
                    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                    <script>
                    @section('css')
                        <style>
                            @media (max-width: 1024px) {
                                .flex-row {
                                    flex-direction: column !important;
                                }
                                #map {
                                    margin-top: 2rem;
                                }
                            }
                        </style>
                    @endsection
                    document.addEventListener("DOMContentLoaded", function() {
                        // Coordinates from DB
                        let farmer = [{{ Auth::user()->farmer->location_longitude }}, {{ Auth::user()->farmer->location_latitude }}];   // lng, lat
                        let agrovet = [{{ $fertilizer->agrovet->location_longitude }}, {{ $fertilizer->agrovet->location_latitude }}]; // lng, lat
                        let map = L.map('map');
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors',
                            subdomains: ['a','b','c']
                        }).addTo(map);
                        let farmerCoords = [{{ Auth::user()->farmer->location_latitude }}, {{ Auth::user()->farmer->location_longitude }}];
                        let agrovetCoords = [{{ $fertilizer->agrovet->location_latitude }}, {{ $fertilizer->agrovet->location_longitude }}];
                        // Auto-fit map so both are visible
                        map.fitBounds([farmerCoords, agrovetCoords]);
                        // OSM tiles
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                              // helps distribute load
                        }).addTo(map);
                        // Custom red icon for Agrovet
                        var redIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                            shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        });
                        L.marker([{{ $fertilizer->agrovet->location_latitude }}, {{ $fertilizer->agrovet->location_longitude }}], {icon: redIcon})
                            .addTo(map)
                            .bindPopup("Agrovet Location")
                            .openPopup();

                        // Farmer marker
                        L.marker([{{ Auth::user()->farmer->location_latitude }}, {{ Auth::user()->farmer->location_longitude }}])
                            .addTo(map)
                            .bindPopup("Your Location")

                        // Fetch route from ORS
                        async function getRoute() {
                            let res = await fetch("https://api.openrouteservice.org/v2/directions/driving-car", {
                                method: "POST",
                                headers: {
                                    "Authorization": "{{ env('OPENSTREETMAP_API_KEY') }}", // Replace with your key
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    coordinates: [farmer, agrovet]
                                })
                            });

                            let data = await res.json();

                            if (data.routes) {
                                let route = data.routes[0].summary;
                                document.getElementById("output").innerHTML =
                                    "Distance: " + (route.distance / 1000).toFixed(2) + " km" +
                                    " | Duration: " + (route.duration / 60).toFixed(1) + " mins";

                                // Draw route
                                let coords = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
                                L.polyline(coords, { color: "blue", weight: 5 }).addTo(map);
                            } else {
                                document.getElementById("output").innerHTML = "No route found.";
                            }
                        }

                        getRoute();
                    });
                    </script>
                    </dl>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>