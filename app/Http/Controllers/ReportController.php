<?php

namespace App\Http\Controllers;

use App\Models\IncomingGood;
use App\Models\OutgoingGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
     * Export reports to JSON (legacy)
     */
    public function export(Request $request)
    {
        $reports = $this->buildQuery($request);

        return response()->json($reports);
    }

    /**
     * Export reports to PDF
     */
    public function exportPdf(Request $request)
    {
        $reports = $this->buildQuery($request);

        $filters = [
            'entry_date' => $request->entry_date,
            'exit_date' => $request->exit_date,
            'category' => $request->category,
            'type' => $request->type,
            'search' => $request->search,
        ];

        // Calculate totals
        $totals = [
            'incoming' => $reports->sum('incoming'),
            'outgoing' => $reports->sum('outgoing'),
            'stock' => $reports->sum('stock'),
        ];

        $pdf = Pdf::loadView('exports.reports-pdf', compact('reports', 'filters', 'totals'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('inventory-report-' . date('Y-m-d') . '.pdf');
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
            $incomingQuery = IncomingGood::where('product', $product);
            $outgoingQuery = OutgoingGood::where('product', $product);

            // Apply date filters to incoming/outgoing queries
            if ($request->filled('entry_date')) {
                $incomingQuery->whereDate('date', '>=', $request->entry_date);
            }
            if ($request->filled('exit_date')) {
                $incomingQuery->whereDate('date', '<=', $request->exit_date);
                $outgoingQuery->whereDate('date', '<=', $request->exit_date);
            }

            $totalIncoming = $incomingQuery->sum('incoming');
            $totalOutgoing = $outgoingQuery->sum('outgoing');
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

        // Apply category filter
        if ($request->filled('category')) {
            $reports = $reports->filter(function ($item) use ($request) {
                return $item['category'] === $request->category;
            });
        }

        // Apply type filter
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

        // Apply search filter
        if ($request->filled('search')) {
            $reports = $reports->filter(function ($item) use ($request) {
                return stripos($item['product'], $request->search) !== false;
            });
        }

        return $reports->sortBy('product')->values();
    }
}


