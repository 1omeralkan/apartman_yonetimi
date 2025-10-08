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

        return view('dashboard', compact(
            'totalSites','totalBlocks','totalApartments','totalFlats',
            'recentSites','recentBlocks','recentApartments','flatStatusCounts'
        ));
    }
}


