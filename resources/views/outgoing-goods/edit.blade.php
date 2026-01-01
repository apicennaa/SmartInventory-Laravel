@extends('layouts.app')

@section('title', 'Edit Outgoing Goods')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700" rel="stylesheet"/>

<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('outgoing-goods.index') }}" class="text-gray-400 hover:text-gray-600">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Outgoing Goods</h1>
            <p class="text-sm text-gray-500 mt-1">Update product information</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">Product Information</h2>
        <p class="text-sm text-gray-500 mt-1">Update the details below</p>
    </div>

    <form action="{{ route('outgoing-goods.update', $outgoingGood->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="product" 
                    value="{{ old('product', $outgoingGood->product) }}" 
                    required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('product') border-red-500 @enderror"
                    placeholder="Enter product name">
                @error('product')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Category <span class="text-red-500">*</span>
                </label>
                <select 
                    name="category" 
                    required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('category') border-red-500 @enderror">
                    <option value="">Select Category</option>
                    <option value="Device" {{ old('category', $outgoingGood->category) == 'Device' ? 'selected' : '' }}>Device</option>
                    <option value="Liquid" {{ old('category', $outgoingGood->category) == 'Liquid' ? 'selected' : '' }}>Liquid</option>
                    <option value="Coil & Cartridge" {{ old('category', $outgoingGood->category) == 'Coil & Cartridge' ? 'selected' : '' }}>Coil & Cartridge</option>
                    <option value="Battery & Charger" {{ old('category', $outgoingGood->category) == 'Battery & Charger' ? 'selected' : '' }}>Battery & Charger</option>
                    <option value="Accessories" {{ old('category', $outgoingGood->category) == 'Accessories' ? 'selected' : '' }}>Accessories</option>
                    <option value="Atomizer" {{ old('category', $outgoingGood->category) == 'Atomizer' ? 'selected' : '' }}>Atomizer</option>
                    <option value="Tools & Spare Part" {{ old('category', $outgoingGood->category) == 'Tools & Spare Part' ? 'selected' : '' }}>Tools & Spare Part</option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Quantity <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="outgoing" 
                    value="{{ old('outgoing', $outgoingGood->outgoing) }}" 
                    min="1" 
                    required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('outgoing') border-red-500 @enderror"
                    placeholder="Enter quantity">
                @error('outgoing')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Store <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="store" 
                    value="{{ old('store', $outgoingGood->store) }}" 
                    required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('store') border-red-500 @enderror"
                    placeholder="Enter store name">
                @error('store')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Date <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    name="date" 
                    value="{{ old('date', $outgoingGood->date->format('Y-m-d')) }}" 
                    required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('date') border-red-500 @enderror">
                @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="{{ route('outgoing-goods.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors shadow-sm hover:shadow-md flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">save</span>
                Update Product
            </button>
        </div>
    </form>
</div>
@endsection
