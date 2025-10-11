<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function profile(): View
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }
}


