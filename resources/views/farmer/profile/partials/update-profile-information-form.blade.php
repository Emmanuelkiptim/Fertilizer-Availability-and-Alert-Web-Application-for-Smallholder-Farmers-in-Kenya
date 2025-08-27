<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Farmer Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Farmer Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>


        <div>
            <x-input-label for="email" :value="__('Farmer Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    @if(!$user->farmer)
        </form>
    <!-- First-time form: phone + location -->
        <form id="farmerRegisterForm" action="{{ route('farmer.store') }}" method="POST">
            @csrf

            <!-- farmer_phonenumber -->
            <div class="mb-3">
                <label for="farmer_phonenumber" class="form-label">FarmerPhone Number</label>
                <input type="text" name="farmer_phonenumber" id="farmer_phonenumber" class="form-control" required>
            </div>

            <!-- Hidden location fields -->
            <input type="hidden" name="location_latitude" id="location_latitude">
            <input type="hidden" name="location_longitude" id="location_longitude">

            <!-- Button to capture location -->
            <x-primary-button type="button" id="getLocationBtn" class='btn btn-secondary'>{{ __('Capture Location') }}</x-primary-button>

            <!-- Save -->
            <x-primary-button type="submit" class='btn btn-primary mt-3'>{{ __('Save Profile') }}</x-primary-button>
        </form>
        <!-- js for obtaining location-->
        <script>
            document.getElementById('getLocationBtn').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        document.getElementById('location_latitude').value = position.coords.latitude;
                        document.getElementById('location_longitude').value = position.coords.longitude;
                        locationCaptured = true;
                        alert("✅ Location captured!");
                        console.log("Latitude: " + position.coords.latitude);
                        console.log("Longitude: " + position.coords.longitude);
                    }, function(error) {
                        alert("❌ Unable to retrieve your location.");
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            });
            // Ensure location is captured before form submission
            document.getElementById('farmerRegisterForm').addEventListener('submit', function(event) {
                if (!locationCaptured) {
                    event.preventDefault();
                    alert("❌ Please capture your location before submitting the form.");
                }
            });
        </script>

        <!-- edit farmer information-->
    @else
        <form id="farmerUpdateForm" method="POST" action="{{ route('farmer.update') }}" class="mt-6 space-y-6">
            @csrf

            <div>
                <x-input-label for="farmer_phonenumber" :value="__('Edit Farmer Phone Number')" />
                <x-text-input id="farmer_phonenumber" name="farmer_phonenumber" type="text" class="mt-1 block w-full" :value="old('farmer_phonenumber', $user->farmer->farmer_phonenumber ?? 'N/A')" required autofocus autocomplete="farmer_phonenumber" />
                <x-input-error class="mt-2" :messages="$errors->get('farmer_phonenumber')" />
            </div>
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <div>
                <x-input-label for="location_latitude" :value="__('View Location Latitude')" />
                <x-text-input id="location_latitude" name="location_latitude" type="text" class="mt-1 block w-full" :value="old('location_latitude', $user->farmer->location_latitude ?? 'N/A')" readonly/>
                <x-input-error class="mt-2" :messages="$errors->get('location_latitude')" />
            </div>

            <div>
                <x-input-label for="location_longitude" :value="__('View Location Longitude')" />
                <x-text-input id="location_longitude" name="location_longitude" type="text" class="mt-1 block w-full" :value="old('location_longitude', $user->farmer->location_longitude ?? 'N/A')" readonly/>
                <x-input-error class="mt-2" :messages="$errors->get('location_longitude')" />
            </div>

            <div class="flex items-center gap-4">
                

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    @endif
</section>
