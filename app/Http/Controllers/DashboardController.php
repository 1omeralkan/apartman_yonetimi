<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Block;
use App\Models\Apartment;
use App\Models\Flat;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Basit metrikler
        $totalSites = Site::count();
        $totalBlocks = Block::count();
        $totalApartments = Apartment::count();
        $totalFlats = Flat::count();

        // Son kayıtlar
        $recentSites = Site::latest()->take(5)->get(['id','name','site_code','created_at']);
        $recentBlocks = Block::with('site:id,name')->latest()->take(5)->get(['id','name','site_id','created_at']);
        $recentApartments = Apartment::with(['site:id,name','block:id,name'])->latest()->take(5)->get(['id','name','site_id','block_id','created_at']);

        // Durum dağılımları (örnek)
        $flatStatusCounts = Flat::selectRaw("status, COUNT(*) as c")->groupBy('status')->pluck('c','status');

        // Grafik verileri
        $chartData = $this->getChartData();
        $heatmapData = $this->getHeatmapData();

        return view('dashboard', compact(
            'totalSites','totalBlocks','totalApartments','totalFlats',
            'recentSites','recentBlocks','recentApartments','flatStatusCounts',
            'chartData','heatmapData'
        ));
    }

    /**
     * Grafik verilerini hazırla
     */
    private function getChartData(): array
    {
        // Son 12 ayın verileri
        $months = [];
        $sitesData = [];
        $flatsData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $sitesData[] = Site::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $flatsData[] = Flat::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return [
            'months' => $months,
            'sites' => $sitesData,
            'flats' => $flatsData,
            'totalSites' => Site::count(),
            'totalFlats' => Flat::count(),
            'activeUsers' => User::where('is_active', true)->count(),
            'pendingUsers' => User::where('is_approved', false)->count(),
        ];
    }

    /**
     * Heatmap verilerini hazırla
     */
    private function getHeatmapData(): array
    {
        // Site bazında daire dağılımı
        $sites = Site::withCount('flats')->get();
        $heatmap = [];
        
        foreach ($sites as $site) {
            $heatmap[] = [
                'name' => $site->name,
                'value' => $site->flats_count,
                'code' => $site->site_code,
                'color' => $this->getHeatmapColor($site->flats_count)
            ];
        }

        return $heatmap;
    }

    /**
     * Heatmap renk kodunu belirle
     */
    private function getHeatmapColor(int $count): string
    {
        if ($count == 0) return '#e5e7eb'; // Gri
        if ($count <= 5) return '#fef3c7'; // Açık sarı
        if ($count <= 10) return '#fde68a'; // Sarı
        if ($count <= 20) return '#f59e0b'; // Turuncu
        if ($count <= 50) return '#ef4444'; // Kırmızı
        return '#dc2626'; // Koyu kırmızı
    }
}


