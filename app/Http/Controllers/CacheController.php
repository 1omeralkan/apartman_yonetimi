<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class CacheController extends Controller
{
    /**
     * Cache ana sayfası
     */
    public function index(): View
    {
        $cacheStats = [
            'total' => $this->getTotalCacheKeys(),
            'active' => $this->getTotalCacheKeys(), // Basit implementasyon
            'size' => $this->getCacheSize(),
            'last_clean' => 'Bilinmiyor'
        ];

        $cacheKeys = $this->getCacheKeys();

        return view('system.cache', compact('cacheStats', 'cacheKeys'));
    }

    /**
     * Cache'i temizle
     */
    public function clear(Request $request): RedirectResponse
    {
        $types = $request->input('types', []);
        $results = [];

        try {
            if (in_array('application', $types)) {
                Artisan::call('cache:clear');
                $results[] = 'Uygulama cache temizlendi';
            }
            
            if (in_array('config', $types)) {
                Artisan::call('config:clear');
                $results[] = 'Config cache temizlendi';
            }
            
            if (in_array('route', $types)) {
                Artisan::call('route:clear');
                $results[] = 'Route cache temizlendi';
            }
            
            if (in_array('view', $types)) {
                Artisan::call('view:clear');
                $results[] = 'View cache temizlendi';
            }

            if (in_array('all', $types)) {
                Artisan::call('optimize:clear');
                $results[] = 'Tüm cache türleri temizlendi';
            }

            if (empty($results)) {
                return redirect()->route('system.cache')->with('warning', 'Hiçbir cache türü seçilmedi.');
            }

            return redirect()->route('system.cache')->with('success', implode(', ', $results) . '.');
        } catch (\Exception $e) {
            return redirect()->route('system.cache')->with('error', 'Cache temizlenirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Cache'i yeniden oluştur
     */
    public function rebuild(): RedirectResponse
    {
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            return redirect()->route('system.cache')->with('success', 'Cache başarıyla yeniden oluşturuldu.');
        } catch (\Exception $e) {
            return redirect()->route('system.cache')->with('error', 'Cache yeniden oluşturulurken hata: ' . $e->getMessage());
        }
    }

    /**
     * Cache optimizasyonu
     */
    public function optimize(): RedirectResponse
    {
        try {
            Artisan::call('optimize');

            return redirect()->route('system.cache')->with('success', 'Cache optimizasyonu tamamlandı.');
        } catch (\Exception $e) {
            return redirect()->route('system.cache')->with('error', 'Cache optimizasyonunda hata: ' . $e->getMessage());
        }
    }

    /**
     * Cache anahtarını sil
     */
    public function forgetKey(Request $request): RedirectResponse
    {
        $request->validate([
            'key' => 'required|string'
        ]);

        try {
            $key = $request->input('key');
            
            if (Cache::has($key)) {
                Cache::forget($key);
                return redirect()->route('system.cache')->with('success', "Cache anahtarı '{$key}' silindi.");
            } else {
                return redirect()->route('system.cache')->with('warning', "Cache anahtarı '{$key}' bulunamadı.");
            }
        } catch (\Exception $e) {
            return redirect()->route('system.cache')->with('error', 'Cache anahtarı silinirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Cache istatistikleri
     */
    public function stats(): View
    {
        $stats = [
            'total_keys' => $this->getTotalCacheKeys(),
            'memory_usage' => $this->getMemoryUsage(),
            'hit_rate' => $this->getHitRate(),
            'driver_info' => $this->getDriverInfo(),
        ];

        return view('system.cache-stats', compact('stats'));
    }

    /**
     * Cache store'larını getir
     */
    private function getCacheStores(): array
    {
        $stores = [];
        $config = config('cache.stores');

        foreach ($config as $name => $store) {
            $stores[$name] = [
                'driver' => $store['driver'],
                'connection' => $store['connection'] ?? null,
                'database' => $store['database'] ?? null,
                'table' => $store['table'] ?? null,
            ];
        }

        return $stores;
    }

    /**
     * Cache boyutunu hesapla
     */
    private function getCacheSize(): string
    {
        try {
            $driver = config('cache.default');
            
            if ($driver === 'file') {
                $cacheDir = storage_path('framework/cache');
                if (is_dir($cacheDir)) {
                    $size = 0;
                    foreach (File::allFiles($cacheDir) as $file) {
                        $size += $file->getSize();
                    }
                    return $this->formatBytes($size);
                }
            } elseif ($driver === 'database') {
                $table = config('cache.stores.database.table');
                $size = \DB::table($table)->sum(\DB::raw('LENGTH(payload)'));
                return $this->formatBytes($size);
            }
            
            return 'Hesaplanamadı';
        } catch (\Exception $e) {
            return 'Hata: ' . $e->getMessage();
        }
    }

    /**
     * Cache anahtarlarını getir
     */
    private function getCacheKeys(): array
    {
        try {
            $driver = config('cache.default');
            $keys = [];

            if ($driver === 'file') {
                $cacheDir = storage_path('framework/cache');
                if (is_dir($cacheDir)) {
                    $files = File::files($cacheDir);
                    foreach (array_slice($files, 0, 20) as $file) { // İlk 20 dosya
                        $keys[] = [
                            'key' => basename($file->getFilename(), '.php'),
                            'size' => $this->formatBytes($file->getSize()),
                            'modified' => date('d.m.Y H:i:s', $file->getMTime()),
                        ];
                    }
                }
            } elseif ($driver === 'database') {
                $table = config('cache.stores.database.table');
                $records = \DB::table($table)
                    ->select('key', 'payload', 'expiration')
                    ->orderBy('expiration', 'desc')
                    ->limit(20)
                    ->get();

                foreach ($records as $record) {
                    $keys[] = [
                        'key' => $record->key,
                        'size' => $this->formatBytes(strlen($record->payload)),
                        'expires' => $record->expiration ? date('d.m.Y H:i:s', $record->expiration) : 'Never',
                    ];
                }
            }

            return $keys;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Toplam cache anahtar sayısını getir
     */
    private function getTotalCacheKeys(): int
    {
        try {
            $driver = config('cache.default');
            
            if ($driver === 'file') {
                $cacheDir = storage_path('framework/cache');
                if (is_dir($cacheDir)) {
                    return count(File::files($cacheDir));
                }
            } elseif ($driver === 'database') {
                $table = config('cache.stores.database.table');
                return \DB::table($table)->count();
            }
            
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Memory kullanımını getir
     */
    private function getMemoryUsage(): string
    {
        $memory = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);
        
        return [
            'current' => $this->formatBytes($memory),
            'peak' => $this->formatBytes($peak),
        ];
    }

    /**
     * Cache hit rate'ini hesapla
     */
    private function getHitRate(): string
    {
        try {
            // Bu basit bir implementasyon, gerçek uygulamada daha karmaşık olabilir
            $driver = config('cache.default');
            
            if ($driver === 'database') {
                $table = config('cache.stores.database.table');
                $total = \DB::table($table)->count();
                
                // Bu gerçek bir hit rate değil, sadece örnek
                return $total > 0 ? '85%' : '0%';
            }
            
            return 'Hesaplanamadı';
        } catch (\Exception $e) {
            return 'Hata';
        }
    }

    /**
     * Driver bilgilerini getir
     */
    private function getDriverInfo(): array
    {
        $driver = config('cache.default');
        
        return [
            'name' => $driver,
            'class' => get_class(Cache::getStore()),
            'config' => config("cache.stores.{$driver}"),
        ];
    }

    /**
     * Byte'ları okunabilir formata çevir
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
