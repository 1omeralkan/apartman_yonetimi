<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\SettlementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Site yönetimi (admin, site_manager, super_admin)
    Route::middleware(['role:admin,site_manager,super_admin'])->group(function () {
        Route::resource('sites', SiteController::class);
        Route::resource('blocks', BlockController::class);
        Route::resource('sites.blocks', BlockController::class);
        Route::resource('apartments', ApartmentController::class);
        Route::get('settlement', [SettlementController::class, 'index'])->name('settlement.index');
        Route::get('settlement/block/{block}', [SettlementController::class, 'block'])->name('settlement.block');
        Route::get('settlement/apartment/{apartment}', [SettlementController::class, 'apartment'])->name('settlement.apartment');
        Route::get('settlement/flat/{flat}/assign', [SettlementController::class, 'assignForm'])->name('settlement.assign.form');
        Route::post('settlement/flat/{flat}/assign', [SettlementController::class, 'assign'])->name('settlement.assign');
    });
});
