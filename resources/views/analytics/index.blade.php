@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<div class="bg-[#f6f6f8] min-h-screen font-sans">
    <main class="flex-1 flex flex-col h-full">
        
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-10 border-b border-[#dbdde6] px-6 py-4">
            <div class="max-w-[1400px] mx-auto flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-[#111218] text-2xl font-bold">Inventory Intelligence</h2>
                    <p class="text-[#616989] text-sm">Monitoring health score dan analisis ABC</p>
                </div>
                
                <form action="{{ route('analisis.index') }}" method="GET" class="flex items-center gap-3">
                    <div class="relative">
                        <select name="category" onchange="this.form.submit()" class="appearance-none bg-white border border-[#dbdde6] rounded-xl px-4 py-2 pr-10 text-sm font-medium focus:ring-primary focus:border-primary">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-2 text-gray-400 pointer-events-none">filter_list</span>
                    </div>
                    @if(request('category'))
                        <a href="{{ route('analisis.index') }}" class="text-xs text-red-500 hover:underline">Reset</a>
                    @endif
                </form>
            </div>
        </header>

        <div class="flex-1 p-6">
            <div class="max-w-[1400px] mx-auto flex flex-col gap-8">
                
                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="bg-white rounded-xl p-5 border border-[#dbdde6] shadow-sm">
                        <p class="text-[#616989] text-xs font-medium uppercase tracking-wide">Total Products</p>
                        <p class="text-[#111218] text-2xl font-bold mt-1">{{ $stats['total_products'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-5 border border-[#dbdde6] shadow-sm">
                        <p class="text-[#616989] text-xs font-medium uppercase tracking-wide text-green-600">Fast Moving</p>
                        <p class="text-[#111218] text-2xl font-bold mt-1">{{ $stats['fast_moving'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-5 border border-red-200 shadow-sm bg-red-50/30">
                        <p class="text-red-600 text-xs font-medium uppercase tracking-wide">Critical Health</p>
                        <p class="text-[#111218] text-2xl font-bold mt-1">{{ $stats['unhealthy'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-5 border border-orange-200 shadow-sm">
                        <p class="text-orange-600 text-xs font-medium uppercase tracking-wide">Need Restock</p>
                        <p class="text-[#111218] text-2xl font-bold mt-1">{{ $stats['needs_restock'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-5 border border-blue-200 shadow-sm">
                        <p class="text-blue-600 text-xs font-medium uppercase tracking-wide">ABC Class A</p>
                        <p class="text-[#111218] text-2xl font-bold mt-1">{{ $stats['abc_a'] }}</p>
                    </div>
                </section>

                <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($movement as $item)
                    @php
                        $statusColor = $item->health_status == 'Healthy' ? 'green' : ($item->health_status == 'Warning' ? 'yellow' : 'red');
                    @endphp
                    <div class="bg-white rounded-xl border border-[#dbdde6] shadow-sm p-5 flex flex-col gap-4 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start">
                            <div class="flex flex-col gap-1">
                                <h3 class="font-bold text-[#111218] text-lg">{{ $item->product }}</h3>
                                <span class="text-xs text-[#616989] bg-gray-100 px-2 py-0.5 rounded-md w-fit">{{ $item->category }}</span>
                            </div>
                            <div class="size-10 rounded-full border-2 border-primary text-primary flex items-center justify-center font-bold">
                                {{ $item->abc_class }}
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <span class="bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase border border-{{ $statusColor }}-100">
                                {{ $item->movement_class }} Moving
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 py-3 border-t border-dashed border-gray-100">
                            <div>
                                <p class="text-[#616989] text-[10px] uppercase">Outgoing</p>
                                <p class="text-[#111218] font-bold">{{ number_format($item->total_outgoing) }}</p>
                            </div>
                            <div>
                                <p class="text-[#616989] text-[10px] uppercase">Stock</p>
                                <p class="text-[#111218] font-bold">{{ number_format($item->available_stock) }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <div class="flex justify-between text-xs font-medium">
                                <span class="text-[#616989]">Health Score</span>
                                <span class="text-{{ $statusColor }}-600">{{ $item->health_score }}%</span>
                            </div>
                            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-{{ $statusColor }}-500 transition-all duration-500" style="width: {{ $item->health_score }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-2 mt-auto border-t border-gray-50">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-{{ $statusColor }}-600 flex items-center gap-1">
                                <span class="size-2 rounded-full bg-{{ $statusColor }}-500 {{ $item->health_status != 'Healthy' ? 'animate-pulse' : '' }}"></span>
                                {{ $item->health_status }}
                            </span>
                            @if($item->restock_recommendation == 'Perlu Restock')
                                <span class="text-[10px] bg-red-100 text-red-700 px-2 py-1 rounded font-bold">RE-STOCK</span>
                            @else
                                <span class="text-[10px] text-gray-400">Stock Aman</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </section>
            </div>
        </div>
    </main>
</div>
@endsection