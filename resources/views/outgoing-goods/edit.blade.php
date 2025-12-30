@extends('layouts.app')

@section('title', 'Edit Outgoing Goods')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Outgoing Goods</h1>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('outgoing-goods.update', $outgoingGood->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                <input type="text" name="product" value="{{ old('product', $outgoingGood->product) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">Select Category</option>
                    <option value="Device" {{ old('category', $outgoingGood->category) == 'Device' ? 'selected' : '' }}>Device</option>
                    <option value="Liquid" {{ old('category', $outgoingGood->category) == 'Liquid' ? 'selected' : '' }}>Liquid</option>
                    <option value="Coil & Cartridge" {{ old('category', $outgoingGood->category) == 'Coil & Cartridge' ? 'selected' : '' }}>Coil & Cartridge</option>
                    <option value="Battery & Charger" {{ old('category', $outgoingGood->category) == 'Battery & Charger' ? 'selected' : '' }}>Battery & Charger</option>
                    <option value="Accessories" {{ old('category', $outgoingGood->category) == 'Accessories' ? 'selected' : '' }}>Accessories</option>
                    <option value="Atomizer" {{ old('category', $outgoingGood->category) == 'Atomizer' ? 'selected' : '' }}>Atomizer</option>
                    <option value="Tools & Spare Part" {{ old('category', $outgoingGood->category) == 'Tools & Spare Part' ? 'selected' : '' }}>Tools & Spare Part</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Outgoing</label>
                <input type="number" name="outgoing" value="{{ old('outgoing', $outgoingGood->outgoing) }}" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                @if($errors->has('outgoing'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('outgoing') }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Store</label>
                <input type="text" name="store" value="{{ old('store', $outgoingGood->store) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" name="date" value="{{ old('date', $outgoingGood->date->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
        </div>

        <div class="mt-6 flex space-x-4">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg">
                Update
            </button>
            <a href="{{ route('outgoing-goods.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

