<?php

namespace App\Http\Controllers;

use App\Models\OutgoingGood;
use App\Models\IncomingGood;
use Illuminate\Http\Request;

class OutgoingGoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $outgoingGoods = OutgoingGood::latest()->paginate(10);
        return view('outgoing-goods.index', compact('outgoingGoods'));
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

