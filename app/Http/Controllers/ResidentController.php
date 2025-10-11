<?php

namespace App\Http\Controllers;

use App\Models\FlatResident;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResidentController extends Controller
{
    public function home(): View
    {
        $userId = Auth::id();
        $resident = FlatResident::with(['flat.apartment.block.site','user'])
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->latest()
            ->first();

        return view('resident.home', compact('resident'));
    }

    public function dues(): View
    {
        // Placeholder: aidatlar ve ödemeler daha sonra doldurulacak
        return view('resident.dues');
    }

    public function complaints(): View
    {
        // Placeholder: şikayetlerim sayfası daha sonra doldurulacak
        return view('resident.complaints');
    }

    public function documents(): View
    {
        // Placeholder: belgeler sayfası daha sonra doldurulacak
        return view('resident.documents');
    }

    public function announcements(): View
    {
        // Placeholder: duyurularım sayfası daha sonra doldurulacak
        return view('resident.announcements');
    }
}


