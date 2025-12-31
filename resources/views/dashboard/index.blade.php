@extends('layouts.app')

@section('title', 'WareFlow | Warehouse Analytics')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .font-manrope { font-family: 'Manrope', sans-serif; }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    
    /* Custom Colors to match original design */
    .text-primary-custom { color: #137fec; }
    .bg-primary-custom { background-color: #137fec; }
    .bg-background-light { background-color: #f6f7f8; }
</style>

<div class="font-manrope -m-6 bg-background-light min-h-screen">
    {{-- Header Section --}}
    <header class="flex flex-col md:flex-row md:items-center justify-between bg-white border-b border-gray-200 px-8 py-4 gap-4">
        <div>
            <h2 class="text-xl font-bold">Dashboard Overview</h2>
            <p class="text-xs text-[#617589]">Real-time inventory data analytics</p>
        </div>
        
        <form action="{{ route('dashboard.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex items-center bg-[#f0f2f4] rounded-lg px-3 py-1.5 border border-transparent focus-within:border-blue-500/50">
                <span class="material-symbols-outlined text-sm text-gray-400 mr-2">calendar_today</span>
                <input type="date" name="start_date" value="{{ $startDate }}" class="bg-transparent border-none text-xs focus:ring-0 p-0">
                <span class="mx-2 text-gray-400 text-xs">to</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="bg-transparent border-none text-xs focus:ring-0 p-0">
            </div>
            <button type="submit" class="bg-primary-custom hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">filter_alt</span>
                Apply Filter
            </button>
        </form>
    </header>

    <div class="p-8">
        <div class="max-w-[1400px] mx-auto flex flex-col gap-6">
            
            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Incoming --}}
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="p-2 w-fit bg-blue-50 rounded-lg text-primary-custom">
                        <span class="material-symbols-outlined">input</span>
                    </div>
                    <p class="text-[#617589] text-xs font-bold mt-4 uppercase">Total Incoming</p>
                    <p class="text-2xl font-black mt-1">{{ number_format($totalIncoming) }}</p>
                </div>

                {{-- Outgoing --}}
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="p-2 w-fit bg-orange-50 rounded-lg text-orange-500">
                        <span class="material-symbols-outlined">output</span>
                    </div>
                    <p class="text-[#617589] text-xs font-bold mt-4 uppercase">Total Outgoing</p>
                    <p class="text-2xl font-black mt-1">{{ number_format($totalOutgoing) }}</p>
                </div>

                {{-- Active Products --}}
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="p-2 w-fit bg-purple-50 rounded-lg text-purple-500">
                        <span class="material-symbols-outlined">category</span>
                    </div>
                    <p class="text-[#617589] text-xs font-bold mt-4 uppercase">Active Products</p>
                    <p class="text-2xl font-black mt-1">{{ number_format($totalProducts) }}</p>
                </div>

                {{-- Stock Balance --}}
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="p-2 w-fit bg-emerald-50 rounded-lg text-emerald-500">
                        <span class="material-symbols-outlined">account_balance_wallet</span>
                    </div>
                    <p class="text-[#617589] text-xs font-bold mt-4 uppercase">Stock Balance</p>
                    <p class="text-2xl font-black mt-1">{{ number_format($totalIncoming - $totalOutgoing) }}</p>
                </div>
            </div>

            {{-- Main Chart --}}
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-lg font-bold">Activity Trends</h3>
                        <p class="text-xs text-gray-400">Monthly Comparison of Goods Flow</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-primary-custom"></span>
                            <span class="text-[10px] font-bold uppercase text-gray-500">Incoming</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                            <span class="text-[10px] font-bold uppercase text-gray-500">Outgoing</span>
                        </div>
                    </div>
                </div>
                <div class="relative h-[320px]">
                    <canvas id="warehouseChart"></canvas>
                </div>
            </div>

            {{-- Tables Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-10">
                {{-- Suppliers Table --}}
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold">Top 5 Suppliers</h3>
                        <span class="text-[10px] bg-blue-50 text-primary-custom px-2 py-1 rounded-full font-bold uppercase">Incoming</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-[#617589] text-[10px] font-bold uppercase border-b border-gray-100">
                                <tr>
                                    <th class="pb-3">Supplier Name</th>
                                    <th class="pb-3 text-right">Items Provided</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($topSuppliers as $sup)
                                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors">
                                    <td class="py-4 font-bold">{{ $sup->supplier }}</td>
                                    <td class="py-4 text-right">
                                        <span class="font-mono text-primary-custom font-bold">{{ number_format($sup->total) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="py-10 text-center text-gray-400">No incoming data found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Stores List --}}
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold">Top 5 Destinated Stores</h3>
                        <span class="text-[10px] bg-orange-50 text-orange-500 px-2 py-1 rounded-full font-bold uppercase">Outgoing</span>
                    </div>
                    <div class="flex flex-col gap-3">
                        @forelse($topStores as $store)
                        <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-orange-200 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500 font-bold">
                                    {{ substr($store->store, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold">{{ $store->store }}</p>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-tighter">Retail Partner</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-orange-500">{{ number_format($store->total) }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Units</p>
                            </div>
                        </div>
                        @empty
                        <p class="py-10 text-center text-gray-400">No outgoing data found</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('warehouseChart').getContext('2d');
        
        const labels = @json($months);
        const dataIn = @json($incomingTrend);
        const dataOut = @json($outgoingTrend);

        const gradBlue = ctx.createLinearGradient(0, 0, 0, 400);
        gradBlue.addColorStop(0, 'rgba(19, 127, 236, 0.3)');
        gradBlue.addColorStop(1, 'rgba(19, 127, 236, 0)');

        const gradOrange = ctx.createLinearGradient(0, 0, 0, 400);
        gradOrange.addColorStop(0, 'rgba(249, 115, 22, 0.3)');
        gradOrange.addColorStop(1, 'rgba(249, 115, 22, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Incoming',
                        data: dataIn,
                        borderColor: '#137fec',
                        backgroundColor: gradBlue,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#137fec',
                    },
                    {
                        label: 'Outgoing',
                        data: dataOut,
                        borderColor: '#f97316',
                        backgroundColor: gradOrange,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#f97316',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a2632',
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(156, 163, 175, 0.1)', drawBorder: false },
                        ticks: { color: '#94a3b8', font: { weight: 'bold' } }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { weight: 'bold' } }
                    }
                }
            }
        });
    });
</script>
@endsection