<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Block;
use App\Models\Flat;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FlatsController extends Controller
{
    public function index(Request $request): View
    {
        $siteId = $request->integer('site_id');
        $blockId = $request->integer('block_id');
        $apartmentId = $request->integer('apartment_id');
        $status = $request->get('status');
        $flatType = $request->get('flat_type');
        $floorMin = $request->filled('floor_min') ? (int)$request->get('floor_min') : null;
        $floorMax = $request->filled('floor_max') ? (int)$request->get('floor_max') : null;
        $flatNo = $request->filled('flat_no') ? (int)$request->get('flat_no') : null;

        $query = Flat::query()
            ->with(['apartment:id,name,block_id','apartment.block:id,name,site_id','apartment.block.site:id,name'])
            ->withCount(['residents as active_residents_count' => function($q){ $q->where('status','active'); }])
            ->orderBy('apartment_id')
            ->orderBy('flat_number');

        if ($status) $query->where('status', $status);
        if ($flatType) $query->where('flat_type', $flatType);
        if ($flatNo) $query->where('flat_number', $flatNo);
        if (!is_null($floorMin)) $query->where('floor_number', '>=', $floorMin);
        if (!is_null($floorMax)) $query->where('floor_number', '<=', $floorMax);
        if ($apartmentId) $query->where('apartment_id', $apartmentId);
        if ($blockId) {
            $query->whereHas('apartment', function($q) use ($blockId){ $q->where('block_id', $blockId); });
        }
        if ($siteId) {
            $query->whereHas('apartment.block', function($q) use ($siteId){ $q->where('site_id', $siteId); });
        }

        $flats = $query->paginate(20)->withQueryString();

        $sites = Site::orderBy('name')->get(['id','name']);
        $blocks = $blockId || $siteId ? Block::when($siteId, fn($q)=>$q->where('site_id',$siteId))->orderBy('name')->get(['id','name','site_id']) : collect();
        $apartments = $apartmentId || $blockId ? Apartment::when($blockId, fn($q)=>$q->where('block_id',$blockId))->orderBy('name')->get(['id','name','block_id']) : collect();

        $statusOptions = ['empty'=>'Boş','occupied'=>'Dolu','maintenance'=>'Bakım','renovation'=>'Tadilat'];
        $flatTypes = ['1+0','1+1','2+1','3+1','4+1','5+1'];
        $statusClassMap = ['empty' => 'secondary', 'occupied' => 'success', 'maintenance' => 'warning', 'renovation' => 'warning'];

        return view('flats.index', compact(
            'flats','sites','blocks','apartments',
            'siteId','blockId','apartmentId',
            'status','flatType','floorMin','floorMax','flatNo',
            'statusOptions','flatTypes','statusClassMap'
        ));
    }
}


