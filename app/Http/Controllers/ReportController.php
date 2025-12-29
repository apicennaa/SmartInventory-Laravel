<?php

namespace App\Http\Controllers;

use App\Models\IncomingGood;
use App\Models\OutgoingGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reports = $this->buildQuery($request);
        
        // Manual pagination
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $items = $reports->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $reports->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('reports.index', ['reports' => $paginated]);
    }

    /**
     * Export reports
     */
    public function export(Request $request)
    {
        $query = $this->buildQuery($request);
        $reports = $query->get();

        // TODO: Implement export to PDF/Excel
        return response()->json($reports);
    }

    /**
     * Build query for reports
     */
    private function buildQuery(Request $request)
    {
        // Get all unique products
        $incomingProducts = IncomingGood::select('product')->distinct()->pluck('product');
        $outgoingProducts = OutgoingGood::select('product')->distinct()->pluck('product');
        $allProducts = $incomingProducts->merge($outgoingProducts)->unique();

        // Build report data
        $reports = collect();
        foreach ($allProducts as $product) {
            $totalIncoming = IncomingGood::where('product', $product)->sum('incoming');
            $totalOutgoing = OutgoingGood::where('product', $product)->sum('outgoing');
            $stock = $totalIncoming - $totalOutgoing;
            
            $latestOutgoing = OutgoingGood::where('product', $product)
                ->orderBy('date', 'desc')
                ->first();
            
            $category = IncomingGood::where('product', $product)->first()?->category ?? '';

            $reports->push([
                'product' => $product,
                'incoming' => $totalIncoming,
                'outgoing' => $totalOutgoing,
                'sales_date' => $latestOutgoing?->date,
                'stock' => $stock,
                'category' => $category,
            ]);
        }

        // Apply filters
        if ($request->filled('entry_date')) {
            $reports = $reports->filter(function ($item) use ($request) {
                return $item['sales_date'] && $item['sales_date'] >= $request->entry_date;
            });
        }

        if ($request->filled('exit_date')) {
            $reports = $reports->filter(function ($item) use ($request) {
                return $item['sales_date'] && $item['sales_date'] <= $request->exit_date;
            });
        }

        if ($request->filled('category')) {
            $reports = $reports->filter(function ($item) use ($request) {
                return $item['category'] === $request->category;
            });
        }

        if ($request->filled('type')) {
            if ($request->type === 'incoming') {
                $reports = $reports->filter(function ($item) {
                    return $item['incoming'] > 0;
                });
            } elseif ($request->type === 'outgoing') {
                $reports = $reports->filter(function ($item) {
                    return $item['outgoing'] > 0;
                });
            }
        }

        if ($request->filled('search')) {
            $reports = $reports->filter(function ($item) use ($request) {
                return stripos($item['product'], $request->search) !== false;
            });
        }

        return $reports->sortBy('product')->values();
    }
}

