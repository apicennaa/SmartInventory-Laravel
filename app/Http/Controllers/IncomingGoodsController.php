<?php

namespace App\Http\Controllers;

use App\Models\IncomingGood;
use Illuminate\Http\Request;

class IncomingGoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incomingGoods = IncomingGood::latest()->paginate(10);
        return view('incoming-goods.index', compact('incomingGoods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('incoming-goods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product' => 'required|string|max:255',
            'incoming' => 'required|integer|min:1',
            'category' => 'required|in:Device,Liquid,Coil & Cartridge,Battery & Charger,Accessories,Atomizer,Tools & Spare Part',
            'supplier' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        IncomingGood::create($validated);

        return redirect()->route('incoming-goods.index')
            ->with('success', 'Barang masuk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomingGood $incomingGood)
    {
        return view('incoming-goods.show', compact('incomingGood'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomingGood $incomingGood)
    {
        return view('incoming-goods.edit', compact('incomingGood'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomingGood $incomingGood)
    {
        $validated = $request->validate([
            'product' => 'required|string|max:255',
            'incoming' => 'required|integer|min:1',
            'category' => 'required|in:Device,Liquid,Coil & Cartridge,Battery & Charger,Accessories,Atomizer,Tools & Spare Part',
            'supplier' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $incomingGood->update($validated);

        return redirect()->route('incoming-goods.index')
            ->with('success', 'Barang masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomingGood $incomingGood)
    {
        $incomingGood->delete();

        return redirect()->route('incoming-goods.index')
            ->with('success', 'Barang masuk berhasil dihapus.');
    }
}

