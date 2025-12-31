<?php

namespace App\Http\Controllers;

use App\Models\OutgoingGood;
use App\Models\IncomingGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar kategori unik untuk dropdown filter
        $categories = OutgoingGood::select('category')->distinct()->pluck('category');

        // 1. Agregasi Outgoing
        $query = OutgoingGood::select(
                'product',
                'category',
                DB::raw('SUM(outgoing) as total_outgoing'),
                DB::raw('COUNT(*) as frequency')
            )
            ->groupBy('product', 'category')
            ->orderByDesc('total_outgoing');

        // Logic Filter Kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $movement = $query->get();

        // 2. Fast vs Slow Moving & 3. ABC Analysis
        $count = $movement->count();
        if ($count > 0) {
            $fastLimit = ceil($count * 0.3);
            $slowLimit = floor($count * 0.7);
            $totalOutgoingAll = $movement->sum('total_outgoing');
            $cumulative = 0;

            $movement = $movement->values()->map(function ($item, $index) use ($fastLimit, $slowLimit, $totalOutgoingAll, &$cumulative) {
                // Movement Class
                if ($index < $fastLimit) { $item->movement_class = 'Fast'; }
                elseif ($index >= $slowLimit) { $item->movement_class = 'Slow'; }
                else { $item->movement_class = 'Medium'; }

                // ABC Class
                $percentage = ($item->total_outgoing / max($totalOutgoingAll, 1)) * 100;
                $cumulative += $percentage;
                if ($cumulative <= 70) { $item->abc_class = 'A'; }
                elseif ($cumulative <= 90) { $item->abc_class = 'B'; }
                else { $item->abc_class = 'C'; }

                return $item;
            });
        }

        // 4. Stock & Health Score Calculation
        $stocks = IncomingGood::select('product', 'category', DB::raw('SUM(incoming) as total_incoming'))
            ->groupBy('product', 'category')
            ->get()
            ->keyBy(fn($i) => $i->product . '|' . $i->category);

        $movement = $movement->map(function ($item) use ($stocks) {
            $key = $item->product . '|' . $item->category;
            $incoming = $stocks[$key]->total_incoming ?? 0;
            $available = max($incoming - $item->total_outgoing, 0);

            $movementRate = min(($item->total_outgoing / max($incoming, 1)) * 100, 100);
            $stockAvailability = min(($available / max($incoming, 1)) * 100, 100);
            
            $healthScore = (0.4 * $movementRate) + (0.3 * $stockAvailability) + (0.3 * $movementRate);
            $item->health_score = round($healthScore, 1);
            $item->available_stock = $available;

            $item->health_status = $healthScore >= 80 ? 'Healthy' : ($healthScore >= 50 ? 'Warning' : 'Critical');
            $item->restock_recommendation = ($available < 10 && $item->movement_class === 'Fast') ? 'Perlu Restock' : 'Aman';

            return $item;
        });

        // 5. Hitung STATS untuk kartu KPI (Berdasarkan data yang sudah difilter)
        $stats = [
            'total_products' => $movement->count(),
            'fast_moving'    => $movement->where('movement_class', 'Fast')->count(),
            'unhealthy'      => $movement->where('health_score', '<', 50)->count(),
            'needs_restock'  => $movement->where('restock_recommendation', 'Perlu Restock')->count(),
            'abc_a'          => $movement->where('abc_class', 'A')->count(),
        ];

        return view('analytics.index', compact('movement', 'categories', 'stats'));
    }
}