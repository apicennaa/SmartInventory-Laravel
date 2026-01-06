@extends('layouts.app')

@section('title', 'Outgoing Goods')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700" rel="stylesheet"/>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Outgoing Goods</h1>
            <p class="text-sm text-gray-500 mt-1">Manage outgoing inventory items</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('outgoing-goods.create') }}" class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-lg font-medium transition-colors shadow-sm hover:shadow-md">
                <span class="material-symbols-outlined text-lg">add</span>
                Add Outgoing Goods
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase mb-1">Total Items</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($stats['total_items']) }}</p>
            </div>
            <div class="p-3 bg-orange-50 rounded-lg">
                <span class="material-symbols-outlined text-orange-600">output</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase mb-1">Total Quantity</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($stats['total_quantity']) }}</p>
            </div>
            <div class="p-3 bg-red-50 rounded-lg">
                <span class="material-symbols-outlined text-red-600">remove_shopping_cart</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase mb-1">Unique Stores</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($stats['unique_stores']) }}</p>
            </div>
            <div class="p-3 bg-purple-50 rounded-lg">
                <span class="material-symbols-outlined text-purple-600">store</span>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
    <form action="{{ route('outgoing-goods.index') }}" method="GET" class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Product</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                    <option value="">All Categories</option>
                    <option value="Device" {{ request('category') == 'Device' ? 'selected' : '' }}>Device</option>
                    <option value="Liquid" {{ request('category') == 'Liquid' ? 'selected' : '' }}>Liquid</option>
                    <option value="Coil & Cartridge" {{ request('category') == 'Coil & Cartridge' ? 'selected' : '' }}>Coil & Cartridge</option>
                    <option value="Battery & Charger" {{ request('category') == 'Battery & Charger' ? 'selected' : '' }}>Battery & Charger</option>
                    <option value="Accessories" {{ request('category') == 'Accessories' ? 'selected' : '' }}>Accessories</option>
                    <option value="Atomizer" {{ request('category') == 'Atomizer' ? 'selected' : '' }}>Atomizer</option>
                    <option value="Tools & Spare Part" {{ request('category') == 'Tools & Spare Part' ? 'selected' : '' }}>Tools & Spare Part</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Store</label>
                <input type="text" name="store" value="{{ request('store') }}" placeholder="Filter by store..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">filter_alt</span>
                    Filter
                </button>
                <a href="{{ route('outgoing-goods.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center justify-center" title="Reset">
                    <span class="material-symbols-outlined text-lg">refresh</span>
                </a>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Store</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($outgoingGoods as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center mr-3">
                                <span class="material-symbols-outlined text-orange-600 text-lg">output</span>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $item->product }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $item->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded">{{ $item->category }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-800">
                            {{ number_format($item->outgoing) }} units
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->store }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->date->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('outgoing-goods.edit', $item->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <form action="{{ route('outgoing-goods.destroy', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" onclick="return confirm('Are you sure you want to delete this item?')" title="Delete">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <span class="material-symbols-outlined text-gray-400 text-5xl mb-3">output</span>
                            <p class="text-gray-500 font-medium">No outgoing goods found</p>
                            <p class="text-sm text-gray-400 mt-1">Try adjusting your filters or add a new item</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($outgoingGoods->hasPages())
    <div class="p-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing {{ $outgoingGoods->firstItem() }} to {{ $outgoingGoods->lastItem() }} of {{ $outgoingGoods->total() }} results
            </div>
            <div>
                {{ $outgoingGoods->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
