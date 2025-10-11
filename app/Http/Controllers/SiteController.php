<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteStoreRequest;
use App\Http\Requests\SiteUpdateRequest;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sites = Site::latest()->paginate(10);
        $statusClassMap = ['active' => 'success','inactive' => 'secondary','maintenance' => 'warning'];
        return view('sites.index', compact('sites','statusClassMap'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('sites.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SiteStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        // Türetilen alanları hesapla
        $data['total_apartments'] = max(0, (int)$data['total_blocks'] * (int)$data['apartments_per_block']);
        $data['total_floors'] = max(0, (int)$data['total_apartments'] * (int)$data['floors_per_apartment']);
        Site::create($data);
        return redirect()->route('sites.index')->with('success', 'Site başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site): View
    {
        $statusClassMap = ['active' => 'success','inactive' => 'secondary','maintenance' => 'warning'];
        return view('sites.show', compact('site','statusClassMap'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site): View
    {
        return view('sites.edit', compact('site'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SiteUpdateRequest $request, Site $site): RedirectResponse
    {
        $data = $request->validated();
        // Türetilen alanları hesapla
        $data['total_apartments'] = max(0, (int)$data['total_blocks'] * (int)$data['apartments_per_block']);
        $data['total_floors'] = max(0, (int)$data['total_apartments'] * (int)$data['floors_per_apartment']);
        $site->update($data);
        return redirect()->route('sites.index')->with('success', 'Site güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site): RedirectResponse
    {
        $site->delete();
        return redirect()->route('sites.index')->with('success', 'Site silindi.');
    }
}
