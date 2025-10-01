<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ApartmentController;

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
    return view('welcome');
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
    });
});
