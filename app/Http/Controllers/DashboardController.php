<?php

namespace App\Http\Controllers;

use App\Models\IncomingGood;
use App\Models\OutgoingGood;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // =========================
        // Filter Tanggal (Default: bulan ini)
        // =========================
        $startDate = $request->input(
            'start_date',
            Carbon::now()->startOfMonth()->toDateString()
        );

        $endDate = $request->input(
            'end_date',
            Carbon::now()->endOfMonth()->toDateString()
        );

        // =========================
        // INCOMING
        // =========================
        $incomingQuery = IncomingGood::whereBetween('date', [$startDate, $endDate]);

        $totalIncoming = $incomingQuery->sum('incoming');
        $totalIncomingProducts = $incomingQuery->distinct('product')->count('product');

        // Top 5 Suppliers
        $topSuppliers = IncomingGood::selectRaw(
                'supplier, SUM(incoming) as total'
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('supplier')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // =========================
        // OUTGOING
        // =========================
        $outgoingQuery = OutgoingGood::whereBetween('date', [$startDate, $endDate]);

        $totalOutgoing = $outgoingQuery->sum('outgoing');
        $totalOutgoingProducts = $outgoingQuery->distinct('product')->count('product');

        // Top 5 Stores
        $topStores = OutgoingGood::selectRaw(
                'store, SUM(outgoing) as total'
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('store')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // =========================
        // MONTHLY TREND (Incoming & Outgoing)
        // =========================
        $incomingMonthly = IncomingGood::selectRaw(
                'MONTH(date) as month, SUM(incoming) as total'
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $outgoingMonthly = OutgoingGood::selectRaw(
                'MONTH(date) as month, SUM(outgoing) as total'
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = $incomingMonthly->pluck('month')->map(function ($m) {
            return date('F', mktime(0, 0, 0, $m, 1));
        });

        $incomingTrend = $incomingMonthly->pluck('total');
        $outgoingTrend = $outgoingMonthly->pluck('total');

        // =========================
        // TOTAL UNIQUE PRODUCTS (FIX ERROR)
        // =========================
        $totalProducts = collect([
            $totalIncomingProducts,
            $totalOutgoingProducts
        ])->max();

        return view('dashboard.index', compact(
            'startDate',
            'endDate',
            'totalIncoming',
            'totalOutgoing',
            'totalProducts',
            'topSuppliers',
            'topStores',
            'months',
            'incomingTrend',
            'outgoingTrend'
        ));
    }
}
