<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Block;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettlementController extends Controller
{
    public function index(Request $request): View
    {
        $sites = Site::orderBy('name')->get(['id','name']);
        $selectedSiteId = $request->integer('site_id');
        $selectedSite = null;
        $blocks = collect();

        if ($selectedSiteId) {
            $selectedSite = Site::with(['blocks' => function($q){ $q->orderBy('name'); }])->find($selectedSiteId);
            $blocks = $selectedSite?->blocks ?? collect();
        }

        return view('settlement.index', compact('sites','selectedSite','blocks','selectedSiteId'));
    }

    public function block(Block $block): View
    {
        $block->load(['site','apartments' => function($q){ $q->orderBy('name'); }]);
        $apartments = $block->apartments;
        return view('settlement.block', compact('block','apartments'));
    }

    public function apartment(Apartment $apartment): View
    {
        $apartment->load(['block.site']);
        $flats = $apartment->flats()->orderBy('flat_number')->get();
        // Katlara göre gruplama (1..N)
        $flatsByFloor = $flats->groupBy('floor_number')->sortKeys();
        return view('settlement.apartment', [
            'apartment' => $apartment,
            'flatsByFloor' => $flatsByFloor,
            'flatsPerFloor' => max(1, (int)$apartment->flats_per_floor),
        ]);
    }
}


