@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Report Preview</h1>
</div>

<!-- Report Filter Modal -->
<div id="reportFilterModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Report</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Entry Date</label>
                    <input type="date" name="entry_date" value="{{ request('entry_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Exit Date</label>
                    <input type="date" name="exit_date" value="{{ request('exit_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Category</label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">All Types</option>
                        <option value="incoming" {{ request('type') == 'incoming' ? 'selected' : '' }}>Choose Incoming Goods</option>
                        <option value="outgoing" {{ request('type') == 'outgoing' ? 'selected' : '' }}>Choose Outgoing Goods</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6 flex space-x-4">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                    Submit
                </button>
                <button type="reset" class="flex-1 bg-white border border-purple-600 text-purple-600 px-4 py-2 rounded-lg">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div class="flex space-x-4">
                <input type="text" name="search" placeholder="Search Product" value="{{ request('search') }}" onchange="this.form.submit()" form="searchForm" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <select name="date" onchange="this.form.submit()" form="searchForm" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option>Date</option>
                </select>
            </div>
            <div class="flex space-x-4">
                <div class="relative">
                    <button onclick="openModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Report Filter
                    </button>
                </div>
                <div class="relative">
                    <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                        Export
                    </button>
                </div>
            </div>
        </div>
        <form id="searchForm" action="{{ route('reports.index') }}" method="GET" class="hidden">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="entry_date" value="{{ request('entry_date') }}">
            <input type="hidden" name="exit_date" value="{{ request('exit_date') }}">
            <input type="hidden" name="category" value="{{ request('category') }}">
            <input type="hidden" name="type" value="{{ request('type') }}">
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="rounded">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PRODUCT</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">INCOMING</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OUTGOING</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SALES</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STOCK</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTION</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reports as $report)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded">
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-500 rounded flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $report['product'] }}</div>
                                <div class="text-xs text-gray-500">Product Details</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $report['incoming'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $report['outgoing'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            @if($report['sales_date'])
                                {{ \Carbon\Carbon::parse($report['sales_date'])->format('d M Y') }}
                            @else
                                -
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $report['stock'] }} ITEMS</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button class="text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No data available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-6 border-t border-gray-200">
        {{ $reports->links() }}
    </div>
</div>

<script>
function openModal() {
    document.getElementById('reportFilterModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('reportFilterModal').classList.add('hidden');
}
</script>
@endsection

