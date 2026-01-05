@extends('layouts.app')

@section('title', 'Incoming Goods')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700" rel="stylesheet"/>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Incoming Goods</h1>
            <p class="text-sm text-gray-500 mt-1">Manage incoming inventory items</p>
        </div>
        <a href="{{ route('incoming-goods.create') }}" class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-lg font-medium transition-colors shadow-sm hover:shadow-md">
            <span class="material-symbols-outlined text-lg">add</span>
            Add Incoming Goods
        </a>
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
            <div class="p-3 bg-blue-50 rounded-lg">
                <span class="material-symbols-outlined text-blue-600">inventory_2</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase mb-1">Total Quantity</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($stats['total_quantity']) }}</p>
            </div>
            <div class="p-3 bg-green-50 rounded-lg">
                <span class="material-symbols-outlined text-green-600">add_shopping_cart</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase mb-1">Unique Suppliers</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($stats['unique_suppliers']) }}</p>
            </div>
            <div class="p-3 bg-purple-50 rounded-lg">
                <span class="material-symbols-outlined text-purple-600">local_shipping</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <!-- Filter by Category -->
                <div class="relative flex-1 max-w-md">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">category</span>
                    <select id="category_filter" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                        <option value="">All Categories</option>
                        <option value="Device">Device</option>
                        <option value="Liquid">Liquid</option>
                        <option value="Coil & Cartridge">Coil & Cartridge</option>
                        <option value="Battery & Charger">Battery & Charger</option>
                        <option value="Accessories">Accessories</option>
                        <option value="Atomizer">Atomizer</option>
                        <option value="Tools & Spare Part">Tools & Spare Part</option>
                    </select>
                </div>

                <!-- Filter by Date Range -->
                <div class="relative flex-1 max-w-md">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">date_range</span>
                    <input type="date" id="date_filter" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                </div>

                <!-- Search Input -->
                <div class="relative flex-1 max-w-md">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">search</span>
                    <input type="text" id="search_filter" placeholder="Search product..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                </div>
            </div>
        </div>
    </div>

    <!-- Table with Data -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($incomingGoods as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mr-3">
                                <span class="material-symbols-outlined text-blue-600 text-lg">inventory_2</span>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $item->product }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $item->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                            {{ number_format($item->incoming) }} units
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded">{{ $item->category }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->supplier }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap" data-date="{{ $item->date->format('Y-m-d') }}">
                        <div class="text-sm text-gray-900">{{ $item->date->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $item->date->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('incoming-goods.edit', $item->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <form action="{{ route('incoming-goods.destroy', $item->id) }}" method="POST" class="inline">
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
                            <span class="material-symbols-outlined text-gray-400 text-5xl mb-3">inventory_2</span>
                            <p class="text-gray-500 font-medium">No incoming goods found</p>
                            <p class="text-sm text-gray-400 mt-1">Get started by adding your first incoming item</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($incomingGoods->hasPages())
    <div class="p-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing {{ $incomingGoods->firstItem() }} to {{ $incomingGoods->lastItem() }} of {{ $incomingGoods->total() }} results
            </div>
            <div>
                {{ $incomingGoods->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categoryFilter = document.getElementById('category_filter');
        const dateFilter = document.getElementById('date_filter');
        const searchFilter = document.getElementById('search_filter');
        const tableRows = document.querySelectorAll('tbody tr');

        function filterTable() {
            const categoryValue = categoryFilter.value.toLowerCase();
            const dateValue = dateFilter.value;  // Format: YYYY-MM-DD
            const searchValue = searchFilter.value.toLowerCase();

            tableRows.forEach(row => {
                const product = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const dateText = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

                // Ambil tanggal dari data-date atribut dalam format YYYY-MM-DD
                const rowDate = row.querySelector('td:nth-child(5)').dataset.date;

                // Cek apakah tanggal yang dipilih cocok dengan format tanggal di row
                const matchesCategory = categoryValue ? category.includes(categoryValue) : true;
                const matchesDate = dateValue ? rowDate === dateValue : true;
                const matchesSearch = product.includes(searchValue);

                if (matchesCategory && matchesDate && matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        categoryFilter.addEventListener('change', filterTable);
        dateFilter.addEventListener('change', filterTable);
        searchFilter.addEventListener('input', filterTable);
    });
</script>
@endsection
