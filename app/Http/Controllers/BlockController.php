<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlockStoreRequest;
use App\Http\Requests\BlockUpdateRequest;
use App\Models\Block;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Block::with('site')->latest();

        if ($request->filled('site_id')) {
            $query->where('site_id', $request->integer('site_id'));
        }

        $blocks = $query->paginate(10)->withQueryString();
        $sites = Site::orderBy('name')->get(['id','name']);
        $selectedSiteId = $request->input('site_id');
        $statusClassMap = ['active' => 'success', 'inactive' => 'secondary', 'maintenance' => 'warning'];
        $statusLabelMap = ['active' => 'Aktif', 'inactive' => 'Pasif', 'maintenance' => 'Bakımda'];

        return view('blocks.index', compact('blocks', 'sites','selectedSiteId','statusClassMap','statusLabelMap'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $sites = Site::orderBy('name')->get(['id','name']);
        return view('blocks.create', compact('sites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlockStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Block::create($data);
        return redirect()->route('blocks.index')->with('success', 'Blok başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Block $block): View
    {
        $sites = Site::orderBy('name')->get(['id','name']);
        return view('blocks.edit', compact('block', 'sites'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Block $block): View
    {
        $block->load('site');
        $statusClassMap = ['active' => 'success', 'inactive' => 'secondary', 'maintenance' => 'warning'];
        $statusLabelMap = ['active' => 'Aktif', 'inactive' => 'Pasif', 'maintenance' => 'Bakımda'];
        $calculatedTotalFlats = (int) $block->total_floors * (int) $block->flats_per_floor;
        return view('blocks.show', compact('block','statusClassMap','statusLabelMap','calculatedTotalFlats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlockUpdateRequest $request, Block $block): RedirectResponse
    {
        $data = $request->validated();
        $block->update($data);
        return redirect()->route('blocks.index')->with('success', 'Blok güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block): RedirectResponse
    {
        $block->delete();
        return redirect()->route('blocks.index')->with('success', 'Blok silindi.');
    }
}

