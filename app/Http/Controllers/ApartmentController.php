<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApartmentStoreRequest;
use App\Http\Requests\ApartmentUpdateRequest;
use App\Models\Apartment;
use App\Models\Block;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Apartment::with(['site','block'])->latest();
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->integer('site_id'));
        }
        if ($request->filled('block_id')) {
            $query->where('block_id', $request->integer('block_id'));
        }
        $apartments = $query->paginate(10)->withQueryString();
        $sites = Site::orderBy('name')->get(['id','name']);
        $blocks = Block::orderBy('name')->get(['id','name']);
        return view('apartments.index', compact('apartments','sites','blocks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $sites = Site::orderBy('name')->get(['id','name']);
        $blocks = Block::orderBy('name')->get(['id','name','site_id']);
        return view('apartments.create', compact('sites','blocks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApartmentStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Apartment::create($data);
        return redirect()->route('apartments.index')->with('success','Apartman oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment): View
    {
        $apartment->load(['site','block']);
        return view('apartments.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment): View
    {
        $sites = Site::orderBy('name')->get(['id','name']);
        $blocks = Block::orderBy('name')->get(['id','name','site_id']);
        return view('apartments.edit', compact('apartment','sites','blocks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ApartmentUpdateRequest $request, Apartment $apartment): RedirectResponse
    {
        $data = $request->validated();
        $apartment->update($data);
        return redirect()->route('apartments.index')->with('success','Apartman güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment): RedirectResponse
    {
        $apartment->delete();
        return redirect()->route('apartments.index')->with('success','Apartman silindi.');
    }
}
