<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fertilizers') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-lg font-medium mb-6 text-gray-900 dark:text-gray-100">Add Fertilizer</h2>
                    <form method="POST" action="{{ route('fertilizers.store') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-gray-700 dark:text-gray-200 mb-2">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                            @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="type" class="block text-gray-700 dark:text-gray-200 mb-2">Type</label>
                            <select name="type" id="type" required 
                                class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                
                                <optgroup label="Based on Nutrient Content">
                                    <option value="single_nutrient_nitrogen" {{ old('type')=='single_nutrient_nitrogen' ? 'selected' : '' }}>
                                        Single-Nutrient Fertilizers / Nitrogen Fertilizers
                                    </option>
                                    <option value="single_nutrient_phosphorus" {{ old('type')=='single_nutrient_phosphorus' ? 'selected' : '' }}>
                                        Single-Nutrient Fertilizers / Phosphorus Fertilizers
                                    </option>
                                    <option value="single_nutrient_potassium" {{ old('type')=='single_nutrient_potassium' ? 'selected' : '' }}>
                                        Single-Nutrient Fertilizers / Potassium Fertilizers
                                    </option>
                                    <option value="compound_multi" {{ old('type')=='compound_multi' ? 'selected' : '' }}>
                                        Compound / Multi-Nutrient Fertilizers
                                    </option>
                                </optgroup>

                                <optgroup label="Based on Origin">
                                    <option value="inorganic" {{ old('type')=='inorganic' ? 'selected' : '' }}>Inorganic Fertilizers</option>
                                    <option value="organic" {{ old('type')=='organic' ? 'selected' : '' }}>Organic Fertilizers</option>
                                </optgroup>

                                <optgroup label="Based on Release Mechanism">
                                    <option value="quick_release" {{ old('type')=='quick_release' ? 'selected' : '' }}>Quick-release Fertilizers</option>
                                    <option value="slow_release" {{ old('type')=='slow_release' ? 'selected' : '' }}>Slow-release Fertilizers</option>
                                    <option value="controlled_release" {{ old('type')=='controlled_release' ? 'selected' : '' }}>Controlled-release Fertilizers</option>
                                    <option value="liquid" {{ old('type')=='liquid' ? 'selected' : '' }}>Liquid Fertilizers</option>
                                </optgroup>

                                <optgroup label="Based on Nutrient Function">
                                    <option value="primary" {{ old('type')=='primary' ? 'selected' : '' }}>Primary Nutrient Fertilizers</option>
                                    <option value="secondary" {{ old('type')=='secondary' ? 'selected' : '' }}>Secondary Nutrient Fertilizers</option>
                                    <option value="micronutrient" {{ old('type')=='micronutrient' ? 'selected' : '' }}>Micronutrient Fertilizers</option>
                                </optgroup>

                                <optgroup label="Specialty Fertilizers">
                                    <option value="bio" {{ old('type')=='bio' ? 'selected' : '' }}>Biofertilizers</option>
                                    <option value="chelated" {{ old('type')=='chelated' ? 'selected' : '' }}>Chelated Fertilizers</option>
                                    <option value="foliar" {{ old('type')=='foliar' ? 'selected' : '' }}>Foliar Fertilizers</option>
                                    <option value="water_soluble" {{ old('type')=='water_soluble' ? 'selected' : '' }}>Water-soluble Fertilizers</option>
                                </optgroup>

                            </select>

                            @error('type') 
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div> 
                            @enderror
                        </div>
                        <div>
                            <label for="qty" class="block text-gray-700 dark:text-gray-200 mb-2">Quantity</label>
                            <input type="number" name="qty" id="qty" value="{{ old('qty') }}" min="0" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                            @error('qty') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="price" class="block text-gray-700 dark:text-gray-200 mb-2">Price</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" min="0" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                            @error('price') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>Save</x-primary-button>
                            <a href="{{ route('fertilizers.index') }}" class="text-gray-600 dark:text-gray-300 hover:underline">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>