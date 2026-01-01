@extends('layouts.app')

@section('title', 'Add Incoming Goods')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700" rel="stylesheet"/>

<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('incoming-goods.index') }}" class="text-gray-400 hover:text-gray-600">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Incoming Goods</h1>
            <p class="text-sm text-gray-500 mt-1">Add new inventory items to the system</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">Product Information</h2>
        <p class="text-sm text-gray-500 mt-1">Fill in the details below</p>
    </div>

    <form action="{{ route('incoming-goods.store') }}" method="POST" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="product" 
                    value="{{ old('product') }}" 
                    required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('product') border-red-500 @enderror"
                    placeholder="Enter product name">
                @error('product')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Quantity <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="incoming" 
                    value="{{ old('incoming') }}" 
                    min="1" 
                    required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('incoming') border-red-500 @enderror"
                    placeholder="Enter quantity">
                @error('incoming')
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
                    <option value="Device" {{ old('category') == 'Device' ? 'selected' : '' }}>Device</option>
                    <option value="Liquid" {{ old('category') == 'Liquid' ? 'selected' : '' }}>Liquid</option>
                    <option value="Coil & Cartridge" {{ old('category') == 'Coil & Cartridge' ? 'selected' : '' }}>Coil & Cartridge</option>
                    <option value="Battery & Charger" {{ old('category') == 'Battery & Charger' ? 'selected' : '' }}>Battery & Charger</option>
                    <option value="Accessories" {{ old('category') == 'Accessories' ? 'selected' : '' }}>Accessories</option>
                    <option value="Atomizer" {{ old('category') == 'Atomizer' ? 'selected' : '' }}>Atomizer</option>
                    <option value="Tools & Spare Part" {{ old('category') == 'Tools & Spare Part' ? 'selected' : '' }}>Tools & Spare Part</option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Supplier <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="supplier" 
                    value="{{ old('supplier') }}" 
                    required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('supplier') border-red-500 @enderror"
                    placeholder="Enter supplier name">
                @error('supplier')
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
                    value="{{ old('date', date('Y-m-d')) }}" 
                    required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('date') border-red-500 @enderror">
                @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="{{ route('incoming-goods.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors shadow-sm hover:shadow-md flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">save</span>
                Save Product
            </button>
        </div>
    </form>
</div>
@endsection
