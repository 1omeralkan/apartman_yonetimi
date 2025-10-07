<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApartmentStoreRequest;
use App\Http\Requests\ApartmentUpdateRequest;
use App\Models\Apartment;
use App\Models\Flat;
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
        $selectedSiteId = $request->input('site_id');
        $selectedBlockId = $request->input('block_id');
        $statusClassMap = ['active' => 'success', 'inactive' => 'secondary', 'maintenance' => 'warning'];
        $statusLabelMap = ['active' => 'Aktif', 'inactive' => 'Pasif', 'maintenance' => 'Bakımda'];
        return view('apartments.index', compact(
            'apartments',
            'sites',
            'blocks',
            'selectedSiteId',
            'selectedBlockId',
            'statusClassMap',
            'statusLabelMap'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $sites = Site::orderBy('name')->get(['id','name']);
        $blocks = Block::orderBy('name')->get(['id','name','site_id']);
        $flatTypes = ['1+0','1+1','2+1','3+1','4+1','5+1'];
        return view('apartments.create', compact('sites','blocks','flatTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApartmentStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        // total_flats'ı otomatik hesapla
        $data['total_flats'] = max(0, (int)$data['total_floors'] * (int)$data['flats_per_floor']);
        $apartment = Apartment::create($data);

        $totalFloors = (int) $apartment->total_floors;
        $flatsPerFloor = (int) $apartment->flats_per_floor;
        $totalFlats = (int) $apartment->total_flats;

        if ($totalFlats > 0) {
            $defaultFlatType = (string) $request->input('flat_type', '2+1');
            $defaultMonthlyDues = $request->filled('monthly_dues') ? (float) $request->input('monthly_dues') : null;
            $defaultHasBalcony = (bool) $request->input('has_balcony', false);
            $defaultGrossArea = $request->filled('gross_area') ? (float) $request->input('gross_area') : null;
            $defaultNetArea = $request->filled('net_area') ? (float) $request->input('net_area') : null;
            $defaultArea = $request->filled('area') ? (float) $request->input('area') : null;

            for ($flatNumber = 1; $flatNumber <= $totalFlats; $flatNumber++) {
                $floorNumber = intdiv($flatNumber - 1, max(1, $flatsPerFloor)) + 1;

                Flat::create([
                    'apartment_id' => $apartment->id,
                    'floor_number' => $floorNumber,
                    'flat_number' => $flatNumber,
                    'flat_type' => $defaultFlatType,
                    'monthly_dues' => $defaultMonthlyDues,
                    'has_balcony' => $defaultHasBalcony,
                    'gross_area' => $defaultGrossArea,
                    'net_area' => $defaultNetArea,
                    'area' => $defaultArea,
                    'status' => 'empty',
                ]);
            }
        }

        return redirect()->route('apartments.index')->with('success','Apartman oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment): View
    {
        $apartment->load(['site','block']);
        $statusClassMap = ['active' => 'success', 'inactive' => 'secondary', 'maintenance' => 'warning'];
        $statusLabelMap = ['active' => 'Aktif', 'inactive' => 'Pasif', 'maintenance' => 'Bakımda'];
        return view('apartments.show', compact('apartment','statusClassMap','statusLabelMap'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment): View
    {
        $sites = Site::orderBy('name')->get(['id','name']);
        $blocks = Block::orderBy('name')->get(['id','name','site_id']);
        // Dairelerden varsayılan değerleri türet
        $firstFlat = Flat::where('apartment_id', $apartment->id)->orderBy('id')->first();
        $flatDefaults = [
            'flat_type' => $firstFlat?->flat_type,
            'monthly_dues' => Flat::where('apartment_id', $apartment->id)->avg('monthly_dues'),
            'has_balcony' => $firstFlat?->has_balcony,
            'gross_area' => Flat::where('apartment_id', $apartment->id)->avg('gross_area'),
            'net_area' => Flat::where('apartment_id', $apartment->id)->avg('net_area'),
            'area' => Flat::where('apartment_id', $apartment->id)->avg('area'),
        ];

        $flatTypes = ['1+0','1+1','2+1','3+1','4+1','5+1'];
        $hasBalconyDefault = (int) ($flatDefaults['has_balcony'] ?? 0);

        return view('apartments.edit', compact('apartment','sites','blocks','flatDefaults','flatTypes','hasBalconyDefault'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ApartmentUpdateRequest $request, Apartment $apartment): RedirectResponse
    {
        $data = $request->validated();

        // Eski flats_per_floor değerini sakla (kat numarası yeniden hesaplamak için)
        $oldFlatsPerFloor = (int) $apartment->flats_per_floor;

        // total_flats'ı otomatik hesapla
        $data['total_flats'] = max(0, (int)$data['total_floors'] * (int)$data['flats_per_floor']);
        $apartment->update($data);
        $apartment->refresh();

        // Apartmana bağlı dairelerde varsayılan alanları toplu güncelle
        $flatPayload = [
            'flat_type' => $request->input('flat_type'),
            'monthly_dues' => $request->filled('monthly_dues') ? (float) $request->input('monthly_dues') : null,
            'has_balcony' => $request->has('has_balcony') ? (bool) $request->input('has_balcony') : null,
            'gross_area' => $request->filled('gross_area') ? (float) $request->input('gross_area') : null,
            'net_area' => $request->filled('net_area') ? (float) $request->input('net_area') : null,
            'area' => $request->filled('area') ? (float) $request->input('area') : null,
        ];

        $flatUpdates = array_filter($flatPayload, static function ($value) {
            return $value !== null && $value !== '';
        });

        if (!empty($flatUpdates)) {
            $apartment->flats()->update($flatUpdates);
        }

        // Toplam kat/dairede artış veya azalışa göre senkronize et
        $currentCount = (int) $apartment->flats()->count();
        $flatsPerFloor = max(1, (int) $apartment->flats_per_floor);
        $desiredTotal = max(0, (int) $apartment->total_floors * $flatsPerFloor);

        // Varsayılanları belirle (gönderilenler öncelikli, yoksa ilk daireden)
        $firstFlat = $apartment->flats()->orderBy('id')->first();
        $defaultFlatType = $request->input('flat_type', $firstFlat->flat_type ?? '2+1');
        $defaultMonthlyDues = $request->filled('monthly_dues') ? (float) $request->input('monthly_dues') : ($firstFlat->monthly_dues ?? null);
        $defaultHasBalcony = $request->has('has_balcony') ? (bool) $request->input('has_balcony') : (bool) ($firstFlat->has_balcony ?? false);
        $defaultGrossArea = $request->filled('gross_area') ? (float) $request->input('gross_area') : ($firstFlat->gross_area ?? null);
        $defaultNetArea = $request->filled('net_area') ? (float) $request->input('net_area') : ($firstFlat->net_area ?? null);
        $defaultArea = $request->filled('area') ? (float) $request->input('area') : ($firstFlat->area ?? null);

        if ($currentCount < $desiredTotal) {
            for ($flatNumber = $currentCount + 1; $flatNumber <= $desiredTotal; $flatNumber++) {
                $floorNumber = intdiv($flatNumber - 1, $flatsPerFloor) + 1;
                Flat::create([
                    'apartment_id' => $apartment->id,
                    'floor_number' => $floorNumber,
                    'flat_number' => $flatNumber,
                    'flat_type' => $defaultFlatType,
                    'monthly_dues' => $defaultMonthlyDues,
                    'has_balcony' => $defaultHasBalcony,
                    'gross_area' => $defaultGrossArea,
                    'net_area' => $defaultNetArea,
                    'area' => $defaultArea,
                    'status' => 'empty',
                ]);
            }
        }
        // Azaldıysa, fazla kalan daireleri sil (sondan)
        if ($currentCount > $desiredTotal) {
            $apartment->flats()
                ->where('flat_number', '>', $desiredTotal)
                ->delete();
        }

        // flats_per_floor değiştiyse mevcut dairelerin floor_number'ını yeniden hesapla
        if ($oldFlatsPerFloor !== $flatsPerFloor) {
            $flats = $apartment->flats()->orderBy('flat_number')->get(['id','flat_number']);
            foreach ($flats as $flat) {
                $newFloor = intdiv($flat->flat_number - 1, $flatsPerFloor) + 1;
                if ($flat->floor_number !== $newFloor) {
                    $flat->floor_number = $newFloor;
                    $flat->save();
                }
            }
        }
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
