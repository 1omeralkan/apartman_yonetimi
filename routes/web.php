<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\FlatsController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\CacheController;

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
        // Rol bazlı yönlendirme middleware'i kullanılacak
        $user = auth()->user();
        
        if ($user->hasRole('super_admin')) {
            return redirect()->route('dashboard');
        } elseif ($user->hasRole(['admin', 'site_manager'])) {
            return redirect()->route('sites.index');
        } elseif ($user->hasRole('resident')) {
            return redirect()->route('resident.home');
        }
    }
    return view('landing');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'check.approval',
])->group(function () {
    
    // Hesabım (her giriş yapmış kullanıcı)
    Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
    Route::post('/account/photo/upload', [AccountController::class, 'uploadPhoto'])->name('account.photo.upload');
    Route::delete('/account/photo/delete', [AccountController::class, 'deletePhoto'])->name('account.photo.delete');
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Admin Onay Sistemi - ÖZEL ROUTE'LAR ÖNCE TANIMLANMALI
        Route::get('users/pending-approval', [UserController::class, 'pendingApproval'])->name('users.pending-approval');
        Route::post('users/bulk-approve', [UserController::class, 'bulkApprove'])->name('users.bulk-approve');
        Route::post('users/bulk-reject', [UserController::class, 'bulkReject'])->name('users.bulk-reject');
        
        // Kullanıcı Yönetimi - Resource Route'lar
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Admin Onay Sistemi - User Parametreli Route'lar
        Route::post('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::post('users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
        
        // Sistem Yönetimi
        Route::prefix('system')->name('system.')->group(function () {
            // Ana sayfa
            Route::get('/', [SystemController::class, 'index'])->name('index');
            Route::post('optimize', [SystemController::class, 'optimize'])->name('optimize');
            
            // Ayarlar
            Route::get('settings', [SystemController::class, 'settings'])->name('settings');
            Route::post('settings', [SystemController::class, 'updateSettings'])->name('update-settings');
            
            // Sağlık kontrolü
            Route::get('health', [SystemController::class, 'health'])->name('health');
            
            // Cache yönetimi
            Route::get('cache', [CacheController::class, 'index'])->name('cache');
            Route::post('cache/clear', [CacheController::class, 'clear'])->name('cache.clear');
            Route::post('cache/rebuild', [CacheController::class, 'rebuild'])->name('cache.rebuild');
            Route::post('cache/optimize', [CacheController::class, 'optimize'])->name('cache.optimize');
            Route::delete('cache/forget-key', [CacheController::class, 'forgetKey'])->name('cache.forget-key');
            Route::get('cache/stats', [CacheController::class, 'stats'])->name('cache.stats');
            
            // Yedekleme
            Route::get('backup', [BackupController::class, 'index'])->name('backup');
            Route::post('backup/database', [BackupController::class, 'createDatabaseBackup'])->name('backup.database');
            Route::post('backup/files', [BackupController::class, 'createFileBackup'])->name('backup.files');
            Route::post('backup/full', [BackupController::class, 'createFullBackup'])->name('backup.full');
            Route::get('backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
            Route::delete('backup/{filename}', [BackupController::class, 'delete'])->name('backup.delete');
            Route::delete('backup', [BackupController::class, 'deleteAll'])->name('backup.delete-all');
            
            // Log yönetimi
            Route::get('logs', [LogController::class, 'index'])->name('logs');
            Route::get('logs/search', [LogController::class, 'search'])->name('logs.search');
            Route::get('logs/stats', [LogController::class, 'stats'])->name('logs.stats');
            Route::get('logs/live', [LogController::class, 'live'])->name('logs.live');
            Route::get('logs/live-data', [LogController::class, 'liveData'])->name('logs.live-data');
            Route::get('logs/download/{filename}', [LogController::class, 'download'])->name('logs.download');
            Route::get('logs/export/{filename}', [LogController::class, 'export'])->name('logs.export');
            Route::post('logs/compress/{filename}', [LogController::class, 'compress'])->name('logs.compress');
            Route::delete('logs/{filename}', [LogController::class, 'delete'])->name('logs.delete');
            Route::delete('logs', [LogController::class, 'clearAll'])->name('logs.clear-all');
            Route::post('logs/clear-old', [LogController::class, 'clearOld'])->name('logs.clear-old');
        });
    });

    // Site yönetimi (admin, site_manager, super_admin)
    Route::middleware(['role:admin,site_manager,super_admin'])->group(function () {
        Route::resource('sites', SiteController::class);
        Route::resource('blocks', BlockController::class);
        Route::resource('sites.blocks', BlockController::class);
        Route::get('api/sites/{site}/blocks', [BlockController::class, 'bySite'])->name('api.blocks.bySite');
        Route::resource('apartments', ApartmentController::class);
        Route::get('flats', [FlatsController::class, 'index'])->name('flats.index');
        Route::get('settlement', [SettlementController::class, 'index'])->name('settlement.index');
        Route::get('settlement/block/{block}', [SettlementController::class, 'block'])->name('settlement.block');
        Route::get('settlement/apartment/{apartment}', [SettlementController::class, 'apartment'])->name('settlement.apartment');
        Route::get('settlement/flat/{flat}', [SettlementController::class, 'flat'])->name('settlement.flat');
        Route::get('settlement/flat/{flat}/assign', [SettlementController::class, 'assignForm'])->name('settlement.assign.form');
        Route::post('settlement/flat/{flat}/assign', [SettlementController::class, 'assign'])->name('settlement.assign');
        Route::delete('settlement/resident/{resident}', [SettlementController::class, 'unassign'])->name('settlement.unassign');
    });

    // Resident
    Route::middleware(['role:resident'])->group(function () {
        Route::get('/resident', [ResidentController::class, 'home'])->name('resident.home');
        Route::get('/resident/dues', [ResidentController::class, 'dues'])->name('resident.dues');
        Route::get('/resident/complaints', [ResidentController::class, 'complaints'])->name('resident.complaints');
        Route::get('/resident/documents', [ResidentController::class, 'documents'])->name('resident.documents');
        Route::get('/resident/announcements', [ResidentController::class, 'announcements'])->name('resident.announcements');
    });
});
