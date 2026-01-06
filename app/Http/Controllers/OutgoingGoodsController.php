<?php

namespace App\Http\Controllers;

use App\Models\OutgoingGood;
use App\Models\IncomingGood;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OutgoingGoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OutgoingGood::query();

        // Filter by search (product name)
        if ($request->filled('search')) {
            $query->where('product', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by store
        if ($request->filled('store')) {
            $query->where('store', 'like', '%' . $request->store . '%');
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Clone query for stats calculation (before pagination)
        $statsQuery = clone $query;

        $outgoingGoods = $query->latest()->paginate(10)->withQueryString();
        
        // Stats based on filtered data
        $stats = [
            'total_items' => $statsQuery->count(),
            'total_quantity' => $statsQuery->sum('outgoing'),
            'unique_stores' => $statsQuery->distinct('store')->count('store'),
        ];

        // Get unique stores for filter dropdown
        $stores = OutgoingGood::select('store')->distinct()->orderBy('store')->pluck('store');

        return view('outgoing-goods.index', compact('outgoingGoods', 'stats', 'stores'));
    }

    /**
     * Export outgoing goods to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = OutgoingGood::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->where('product', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('store')) {
            $query->where('store', 'like', '%' . $request->store . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $outgoingGoods = $query->latest()->get();

        $filters = [
            'search' => $request->search,
            'category' => $request->category,
            'store' => $request->store,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        $pdf = Pdf::loadView('exports.outgoing-goods-pdf', compact('outgoingGoods', 'filters'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('outgoing-goods-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('outgoing-goods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product' => 'required|string|max:255',
            'category' => 'required|in:Device,Liquid,Coil & Cartridge,Battery & Charger,Accessories,Atomizer,Tools & Spare Part',
            'outgoing' => 'required|integer|min:1',
            'store' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        // Validasi stok tersedia berdasarkan product dan category
        $totalIncoming = IncomingGood::where('product', $validated['product'])
            ->where('category', $validated['category'])
            ->sum('incoming');
        $totalOutgoing = OutgoingGood::where('product', $validated['product'])
            ->where('category', $validated['category'])
            ->sum('outgoing');
        $availableStock = $totalIncoming - $totalOutgoing;

        if ($validated['outgoing'] > $availableStock) {
            return back()->withErrors(['outgoing' => 'Stok tidak mencukupi. Stok tersedia: ' . $availableStock])
                ->withInput();
        }

        OutgoingGood::create($validated);

        return redirect()->route('outgoing-goods.index')
            ->with('success', 'Barang keluar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OutgoingGood $outgoingGood)
    {
        return view('outgoing-goods.show', compact('outgoingGood'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OutgoingGood $outgoingGood)
    {
        return view('outgoing-goods.edit', compact('outgoingGood'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OutgoingGood $outgoingGood)
    {
        $validated = $request->validate([
            'product' => 'required|string|max:255',
            'category' => 'required|in:Device,Liquid,Coil & Cartridge,Battery & Charger,Accessories,Atomizer,Tools & Spare Part',
            'outgoing' => 'required|integer|min:1',
            'store' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        // Validasi stok tersedia berdasarkan product dan category (exclude current record)
        $totalIncoming = IncomingGood::where('product', $validated['product'])
            ->where('category', $validated['category'])
            ->sum('incoming');
        $totalOutgoing = OutgoingGood::where('product', $validated['product'])
            ->where('category', $validated['category'])
            ->where('id', '!=', $outgoingGood->id)
            ->sum('outgoing');
        $availableStock = $totalIncoming - $totalOutgoing;

        if ($validated['outgoing'] > $availableStock) {
            return back()->withErrors(['outgoing' => 'Stok tidak mencukupi. Stok tersedia: ' . $availableStock])
                ->withInput();
        }

        $outgoingGood->update($validated);

        return redirect()->route('outgoing-goods.index')
            ->with('success', 'Barang keluar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutgoingGood $outgoingGood)
    {
        $outgoingGood->delete();

        return redirect()->route('outgoing-goods.index')
            ->with('success', 'Barang keluar berhasil dihapus.');
    }
}

