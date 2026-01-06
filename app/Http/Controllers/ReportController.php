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
        // Get filter values
        $entryDate = $request->entry_date;
        $exitDate = $request->exit_date;
        $categoryFilter = $request->category;
        $typeFilter = $request->type;
        $searchFilter = $request->search;

        // Build incoming query with date filter
        $incomingQuery = IncomingGood::query();
        if ($entryDate) {
            $incomingQuery->whereDate('date', '>=', $entryDate);
        }
        if ($exitDate) {
            $incomingQuery->whereDate('date', '<=', $exitDate);
        }

        // Build outgoing query with date filter
        $outgoingQuery = OutgoingGood::query();
        if ($entryDate) {
            $outgoingQuery->whereDate('date', '>=', $entryDate);
        }
        if ($exitDate) {
            $outgoingQuery->whereDate('date', '<=', $exitDate);
        }

        // Get products based on type filter
        if ($typeFilter === 'incoming') {
            // Only show products that have incoming transactions in the date range
            $allProducts = $incomingQuery->select('product')->distinct()->pluck('product');
        } elseif ($typeFilter === 'outgoing') {
            // Only show products that have outgoing transactions in the date range
            $allProducts = $outgoingQuery->select('product')->distinct()->pluck('product');
        } else {
            // Show all products that have any transaction in the date range
            $incomingProducts = $incomingQuery->select('product')->distinct()->pluck('product');
            $outgoingProducts = $outgoingQuery->select('product')->distinct()->pluck('product');
            $allProducts = $incomingProducts->merge($outgoingProducts)->unique();
        }

        // Build report data
        $reports = collect();
        foreach ($allProducts as $product) {
            // Get incoming sum with date filter
            $incomingSum = IncomingGood::where('product', $product);
            if ($entryDate) {
                $incomingSum->whereDate('date', '>=', $entryDate);
            }
            if ($exitDate) {
                $incomingSum->whereDate('date', '<=', $exitDate);
            }
            $totalIncoming = $incomingSum->sum('incoming');

            // Get outgoing sum with date filter
            $outgoingSum = OutgoingGood::where('product', $product);
            if ($entryDate) {
                $outgoingSum->whereDate('date', '>=', $entryDate);
            }
            if ($exitDate) {
                $outgoingSum->whereDate('date', '<=', $exitDate);
            }
            $totalOutgoing = $outgoingSum->sum('outgoing');

            $stock = $totalIncoming - $totalOutgoing;
            
            // Get latest outgoing within date range
            $latestOutgoingQuery = OutgoingGood::where('product', $product);
            if ($entryDate) {
                $latestOutgoingQuery->whereDate('date', '>=', $entryDate);
            }
            if ($exitDate) {
                $latestOutgoingQuery->whereDate('date', '<=', $exitDate);
            }
            $latestOutgoing = $latestOutgoingQuery->orderBy('date', 'desc')->first();
            
            $category = IncomingGood::where('product', $product)->first()?->category 
                ?? OutgoingGood::where('product', $product)->first()?->category 
                ?? '';

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
        if ($categoryFilter) {
            $reports = $reports->filter(function ($item) use ($categoryFilter) {
                return $item['category'] === $categoryFilter;
            });
        }

        // Apply search filter
        if ($searchFilter) {
            $reports = $reports->filter(function ($item) use ($searchFilter) {
                return stripos($item['product'], $searchFilter) !== false;
            });
        }

        return $reports->sortBy('product')->values();
    }
}


