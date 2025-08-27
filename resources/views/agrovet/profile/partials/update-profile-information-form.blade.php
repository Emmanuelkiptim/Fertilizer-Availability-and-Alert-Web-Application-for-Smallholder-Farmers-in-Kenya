<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Agrovet Profile Information') }}
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
            <x-input-label for="name" :value="__('Agrovet Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>


        <div>
            <x-input-label for="email" :value="__('Agrovet Email')" />
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
    </form>
    @if(!$user->agrovet)
    <h3 class="text-lg font-bold mb-4">Register as an Agrovet</h3>
        <form id="agrovetRegisterForm" action="{{ route('agrovet.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="shopname" class="block mb-1">Shop Name</label>
                <input type="text" name="shopname" id="shopname" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="agrovet_phonenumber" class="block mb-1">Phone Number</label>
                <input type="text" name="agrovet_phonenumber" id="agrovet_phonenumber" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block mb-1">Location Latitude</label>
                <input type="hidden" name="location_latitude" id="location_latitude" required>
                <input type="text" id="location_latitude_display" class="w-full p-2 border rounded bg-gray-100" readonly>
            </div>
            <div>
                <label class="block mb-1">Location Longitude</label>
                <input type="hidden" name="location_longitude" id="location_longitude" required>
                <input type="text" id="location_longitude_display" class="w-full p-2 border rounded bg-gray-100" readonly>
            </div>
            <x-primary-button type="button" id="getLocationBtn" class="bg-green-600 text-white px-4 py-2 rounded">{{ __('Detect Location') }}</x-primary-button>
            <x-primary-button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('Register') }}</x-primary-button>
        </form>
        <script>
            let locationCaptured = false;
            document.getElementById('getLocationBtn').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        document.getElementById('location_latitude').value = position.coords.latitude;
                        document.getElementById('location_longitude').value = position.coords.longitude;
                        document.getElementById('location_latitude_display').value = position.coords.latitude;
                        document.getElementById('location_longitude_display').value = position.coords.longitude;
                        locationCaptured = true;
                        alert("✅ Location detected!");
                    }, function(error) {
                        alert("❌ Unable to retrieve your location.");
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            });
            document.getElementById('agrovetRegisterForm').addEventListener('submit', function(event) {
                if (!locationCaptured) {
                    event.preventDefault();
                    alert("❌ Please detect your location before submitting the form.");
                }
            });
        </script>
        @else
        <h3 class="text-lg font-bold mb-4">Your Agrovet Profile</h3>
        <div class="mb-4">
            <strong>Shop Name:</strong> {{ $user->agrovet->shopname }}
        </div>
        <div class="mb-4">
            <strong>Phone Number:</strong> {{ $user->agrovet->agrovet_phonenumber }}
        </div>
        <div class="mb-4">
            <strong>Location Latitude:</strong> {{ $user->agrovet->location_latitude }}
        </div>
        <div class="mb-4">
            <strong>Location Longitude:</strong> {{ $user->agrovet->location_longitude }}
        </div>
        <h4 class="font-semibold mt-6 mb-2">Update Details</h4>
        <form method="POST" action="{{ route('agrovet.update') }}" class="space-y-4">
            @csrf
            <div>
                <label for="shopname" class="block mb-1">Shop Name</label>
                <input type="text" name="shopname" id="shopname" class="w-full p-2 border rounded" value="{{ old('shopname', $user->agrovet->shopname) }}" required>
            </div>
            <div>
                <label for="agrovet_phonenumber" class="block mb-1">Phone Number</label>
                <input type="text" name="agrovet_phonenumber" id="agrovet_phonenumber" class="w-full p-2 border rounded" value="{{ old('agrovet_phonenumber', $user->agrovet->agrovet_phonenumber) }}" required>
            </div>
            
            <x-primary-button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('Update') }}</x-primary-button>
        </form>
        @endif

</section>
