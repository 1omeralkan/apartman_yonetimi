<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Block;
use App\Models\Apartment;
use App\Models\Flat;
use App\Models\FlatResident;
use App\Models\User;
use App\Http\Requests\AssignResidentRequest;
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
        $flats = $apartment->flats()
            ->withCount(['residents as active_residents_count' => function($q){ $q->where('status','active'); }])
            ->orderBy('flat_number')
            ->get();

        // Dinamik durum senkronizasyonu: aktif sakin yoksa empty, varsa occupied
        foreach ($flats as $flat) {
            $desiredStatus = $flat->active_residents_count > 0 ? 'occupied' : 'empty';
            if ($flat->status !== $desiredStatus) {
                $flat->status = $desiredStatus;
                $flat->save();
            }
        }
        // Katlara göre gruplama (1..N)
        $flatsByFloor = $flats->groupBy('floor_number')->sortKeys();
        return view('settlement.apartment', [
            'apartment' => $apartment,
            'flatsByFloor' => $flatsByFloor,
            'flatsPerFloor' => max(1, (int)$apartment->flats_per_floor),
        ]);
    }

    public function assignForm(Flat $flat): View
    {
        $users = User::orderBy('first_name')->get(['id','first_name','last_name','email']);
        return view('settlement.assign', compact('flat','users'));
    }

    public function assign(AssignResidentRequest $request, Flat $flat)
    {
        $data = $request->validated();
        // Güvenlik: aynı kullanıcı başka dairede aktif mi?
        $exists = FlatResident::where('user_id', $data['user_id'])
            ->where('status', 'active')
            ->exists();
        if ($exists) {
            return back()->withErrors(['user_id' => 'Bu kullanıcı zaten bir daireye atanmış.'])->withInput();
        }

        FlatResident::create([
            'flat_id' => $flat->id,
            'user_id' => $data['user_id'],
            'resident_type' => $data['resident_type'],
            'rent_amount' => $data['rent_amount'] ?? null,
            'move_in_date' => $data['move_in_date'],
            'move_out_date' => $data['move_out_date'] ?? null,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        // Dairenin durumunu occupied yap
        $flat->status = 'occupied';
        $flat->save();

        return redirect()->route('settlement.apartment', $flat->apartment_id)->with('success','Sakin atandı.');
    }

    public function flat(Flat $flat): View
    {
        $flat->load(['apartment.block.site','residents.user']);
        $statusOptions = ['empty'=>'Boş','occupied'=>'Dolu','maintenance'=>'Bakım','renovation'=>'Tadilat'];
        $statusClassMap = ['empty' => 'secondary', 'occupied' => 'success', 'maintenance' => 'warning', 'renovation' => 'warning'];
        $residentTypeMap = ['owner'=>'Ev Sahibi','tenant'=>'Kiracı','family_member'=>'Aile Üyesi','guest'=>'Misafir'];
        $residentStatusMap = ['active'=>'Aktif','inactive'=>'Pasif'];
        return view('settlement.flat', compact('flat','statusOptions','statusClassMap','residentTypeMap','residentStatusMap'));
    }

    public function unassign(FlatResident $resident)
    {
        $flatId = $resident->flat_id;
        // Pasifleştir (silmek yerine soft-delete ya da status inactive)
        if (method_exists($resident, 'delete')) {
            $resident->delete();
        } else {
            $resident->status = 'inactive';
            $resident->save();
        }

        // Dairenin durumunu güncelle
        $flat = Flat::find($flatId);
        if ($flat) {
            $hasActive = $flat->residents()->where('status','active')->exists();
            $flat->status = $hasActive ? 'occupied' : 'empty';
            $flat->save();
        }

        return back()->with('success','Sakin kaldırıldı.');
    }
}


