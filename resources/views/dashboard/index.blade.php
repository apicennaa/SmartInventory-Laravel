@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Incoming</h3>
        <p class="text-3xl font-bold text-purple-600">{{ \App\Models\IncomingGood::sum('incoming') }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Outgoing</h3>
        <p class="text-3xl font-bold text-blue-600">{{ \App\Models\OutgoingGood::sum('outgoing') }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Stock</h3>
        <p class="text-3xl font-bold text-green-600">
            {{ \App\Models\IncomingGood::sum('incoming') - \App\Models\OutgoingGood::sum('outgoing') }}
        </p>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Activities</h2>
    <p class="text-gray-500">Recent incoming and outgoing goods will be displayed here.</p>
</div>
@endsection

