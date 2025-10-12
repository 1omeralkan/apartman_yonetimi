<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SystemController extends Controller
{
    /**
     * Sistem ana sayfası
     */
    public function index(): View
    {
        // Sistem bilgileri
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Bilinmiyor',
            'server_os' => PHP_OS,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];

        // Veritabanı bilgileri
        $dbInfo = [
            'driver' => config('database.default'),
            'connection' => config('database.connections.' . config('database.default')),
        ];

        // Uygulama istatistikleri
        $stats = [
            'total_users' => \App\Models\User::count(),
            'active_users' => \App\Models\User::where('is_active', true)->count(),
            'total_sites' => \App\Models\Site::count(),
            'total_apartments' => \App\Models\Apartment::count(),
            'total_flats' => \App\Models\Flat::count(),
            'occupied_flats' => \App\Models\Flat::where('status', 'occupied')->count(),
        ];

        // Disk kullanımı
        $diskUsage = $this->getDiskUsage();

        // Son aktiviteler
        $recentUsers = \App\Models\User::latest()->limit(5)->get();
        $recentSites = \App\Models\Site::latest()->limit(5)->get();

        return view('system.index', compact(
            'systemInfo',
            'dbInfo', 
            'stats',
            'diskUsage',
            'recentUsers',
            'recentSites'
        ));
    }

    /**
     * Sistem ayarları sayfası
     */
    public function settings(): View
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'mail_mailer' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_connection' => config('queue.default'),
        ];

        return view('system.settings', compact('settings'));
    }

    /**
     * Sistem ayarlarını güncelle
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string|in:tls,ssl',
        ]);

        // .env dosyasını güncelle
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        $updates = [
            'APP_NAME' => $request->app_name,
            'APP_URL' => $request->app_url,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
        ];

        if ($request->filled('mail_password')) {
            $updates['MAIL_PASSWORD'] = $request->mail_password;
        }

        foreach ($updates as $key => $value) {
            $envContent = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $envContent
            );
        }

        File::put($envPath, $envContent);

        // Cache'i temizle
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return redirect()->route('system.settings')->with('success', 'Sistem ayarları başarıyla güncellendi.');
    }

    /**
     * Cache yönetimi
     */
    public function cache(): View
    {
        $cacheInfo = [
            'driver' => config('cache.default'),
            'size' => $this->getCacheSize(),
        ];

        return view('system.cache', compact('cacheInfo'));
    }

    /**
     * Cache'i temizle
     */
    public function clearCache(Request $request): RedirectResponse
    {
        $types = $request->input('types', []);

        try {
            if (in_array('application', $types)) {
                Artisan::call('cache:clear');
            }
            if (in_array('config', $types)) {
                Artisan::call('config:clear');
            }
            if (in_array('route', $types)) {
                Artisan::call('route:clear');
            }
            if (in_array('view', $types)) {
                Artisan::call('view:clear');
            }
            if (in_array('all', $types)) {
                Artisan::call('optimize:clear');
            }

            return redirect()->route('system.cache')->with('success', 'Seçilen cache türleri başarıyla temizlendi.');
        } catch (\Exception $e) {
            return redirect()->route('system.cache')->with('error', 'Cache temizlenirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Sistem optimizasyonu
     */
    public function optimize(): RedirectResponse
    {
        try {
            // Basit optimizasyon - sadece temel cache işlemleri
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return redirect()->route('system.index')->with('success', 'Sistem cache\'leri başarıyla temizlendi.');
        } catch (\Exception $e) {
            return redirect()->route('system.index')->with('error', 'Cache temizlenirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Sistem sağlık kontrolü
     */
    public function health(): View
    {
        // Kritik sistem kontrolleri
        $criticalChecks = [
            [
                'name' => 'Veritabanı Bağlantısı',
                'description' => 'MySQL veritabanı bağlantısı kontrolü',
                'status' => $this->checkDatabase()['status'] == 'success' ? 'ok' : 'error',
                'message' => $this->checkDatabase()['message']
            ],
            [
                'name' => 'Storage Yazma İzni',
                'description' => 'Dosya yazma/okuma izinleri kontrolü',
                'status' => $this->checkStorage()['status'] == 'success' ? 'ok' : 'error',
                'message' => $this->checkStorage()['message']
            ],
            [
                'name' => 'Cache Sistemi',
                'description' => 'Cache yazma/okuma işlemleri kontrolü',
                'status' => $this->checkCache()['status'] == 'success' ? 'ok' : ($this->checkCache()['status'] == 'warning' ? 'warning' : 'error'),
                'message' => $this->checkCache()['message']
            ],
            [
                'name' => 'Dosya İzinleri',
                'description' => 'Kritik dizin yazma izinleri kontrolü',
                'status' => $this->checkPermissions()['status'] == 'success' ? 'ok' : 'warning',
                'message' => $this->checkPermissions()['message']
            ],
            [
                'name' => 'PHP Uzantıları',
                'description' => 'Gerekli PHP uzantılarının varlığı',
                'status' => $this->checkPhpExtensions()['status'] == 'success' ? 'ok' : 'error',
                'message' => $this->checkPhpExtensions()['message']
            ],
            [
                'name' => 'Laravel Konfigürasyonu',
                'description' => 'Temel Laravel ayarları kontrolü',
                'status' => $this->checkLaravelConfig()['status'] == 'success' ? 'ok' : 'warning',
                'message' => $this->checkLaravelConfig()['message']
            ]
        ];

        // Performans kontrolleri
        $performanceChecks = [
            [
                'name' => 'Bellek Kullanımı',
                'description' => 'PHP bellek kullanımı',
                'value' => $this->getMemoryUsage(),
                'status' => $this->checkMemoryUsage()['status'],
                'message' => $this->checkMemoryUsage()['message']
            ],
            [
                'name' => 'Disk Kullanımı',
                'description' => 'Sunucu disk alanı kullanımı',
                'value' => $this->getDiskUsage()['used'] . ' / ' . $this->getDiskUsage()['total'],
                'status' => $this->checkDiskUsage()['status'],
                'message' => $this->checkDiskUsage()['message']
            ],
            [
                'name' => 'Cache Boyutu',
                'description' => 'Uygulama cache boyutu',
                'value' => $this->getCacheSize(),
                'status' => $this->checkCacheSize()['status'],
                'message' => $this->checkCacheSize()['message']
            ],
            [
                'name' => 'Log Dosya Boyutu',
                'description' => 'Laravel log dosyalarının toplam boyutu',
                'value' => $this->getLogSize(),
                'status' => $this->checkLogSize()['status'],
                'message' => $this->checkLogSize()['message']
            ]
        ];

        // Güvenlik kontrolleri
        $securityChecks = [
            [
                'name' => 'App Debug Durumu',
                'description' => 'Uygulama debug modu kontrolü',
                'status' => config('app.debug') ? 'warning' : 'ok',
                'message' => config('app.debug') ? 'Debug modu açık - üretim ortamında kapatılmalı' : 'Debug modu kapalı'
            ],
            [
                'name' => 'App Environment',
                'description' => 'Uygulama ortamı kontrolü',
                'status' => config('app.env') == 'production' ? 'ok' : 'warning',
                'message' => 'Ortam: ' . config('app.env')
            ],
            [
                'name' => 'Mail Konfigürasyonu',
                'description' => 'E-posta ayarları kontrolü',
                'status' => $this->checkMail()['status'] == 'success' ? 'ok' : 'warning',
                'message' => $this->checkMail()['message']
            ],
            [
                'name' => 'HTTPS Kullanımı',
                'description' => 'Güvenli bağlantı kontrolü',
                'status' => request()->isSecure() ? 'ok' : 'warning',
                'message' => request()->isSecure() ? 'HTTPS kullanılıyor' : 'HTTP kullanılıyor - HTTPS önerilir'
            ]
        ];

        // Sistem bilgileri
        $systemInfo = [
            ['label' => 'PHP Versiyonu', 'value' => PHP_VERSION],
            ['label' => 'Laravel Versiyonu', 'value' => app()->version()],
            ['label' => 'Sunucu Yazılımı', 'value' => $_SERVER['SERVER_SOFTWARE'] ?? 'Bilinmiyor'],
            ['label' => 'İşletim Sistemi', 'value' => PHP_OS],
            ['label' => 'Bellek Limiti', 'value' => ini_get('memory_limit')],
            ['label' => 'Maksimum Çalışma Süresi', 'value' => ini_get('max_execution_time') . ' saniye'],
            ['label' => 'Upload Maksimum Boyut', 'value' => ini_get('upload_max_filesize')],
            ['label' => 'POST Maksimum Boyut', 'value' => ini_get('post_max_size')],
        ];

        // Genel sağlık durumu hesaplama
        $totalChecks = count($criticalChecks) + count($performanceChecks) + count($securityChecks);
        $healthyCount = 0;
        $warningCount = 0;
        $errorCount = 0;

        foreach (array_merge($criticalChecks, $performanceChecks, $securityChecks) as $check) {
            if ($check['status'] == 'ok') $healthyCount++;
            elseif ($check['status'] == 'warning') $warningCount++;
            else $errorCount++;
        }

        $score = round(($healthyCount / $totalChecks) * 100);
        $status = $score >= 90 ? 'healthy' : ($score >= 70 ? 'warning' : 'error');

        $overallHealth = [
            'status' => $status,
            'score' => $score,
            'message' => $this->getOverallHealthMessage($status, $score),
            'last_check' => now()->format('d.m.Y H:i:s'),
            'healthy_count' => $healthyCount,
            'warning_count' => $warningCount,
            'error_count' => $errorCount
        ];

        // Öneriler
        $recommendations = $this->getRecommendations($criticalChecks, $performanceChecks, $securityChecks);
        $warnings = $this->getWarnings($criticalChecks, $performanceChecks, $securityChecks);

        return view('system.health', compact(
            'criticalChecks',
            'performanceChecks', 
            'securityChecks',
            'systemInfo',
            'overallHealth',
            'recommendations',
            'warnings'
        ));
    }

    /**
     * Disk kullanımını hesapla
     */
    private function getDiskUsage(): array
    {
        $totalBytes = disk_total_space(storage_path());
        $freeBytes = disk_free_space(storage_path());
        $usedBytes = $totalBytes - $freeBytes;

        return [
            'total' => $this->formatBytes($totalBytes),
            'used' => $this->formatBytes($usedBytes),
            'free' => $this->formatBytes($freeBytes),
            'percentage' => round(($usedBytes / $totalBytes) * 100, 2),
        ];
    }

    /**
     * Cache boyutunu hesapla
     */
    private function getCacheSize(): string
    {
        try {
            $cacheDir = storage_path('framework/cache');
            if (is_dir($cacheDir)) {
                $size = 0;
                foreach (File::allFiles($cacheDir) as $file) {
                    $size += $file->getSize();
                }
                return $this->formatBytes($size);
            }
            return '0 B';
        } catch (\Exception $e) {
            return 'Bilinmiyor';
        }
    }

    /**
     * Veritabanı bağlantısını kontrol et
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'success', 'message' => 'Veritabanı bağlantısı başarılı'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Veritabanı bağlantı hatası: ' . $e->getMessage()];
        }
    }

    /**
     * Storage'ı kontrol et
     */
    private function checkStorage(): array
    {
        try {
            $testFile = 'test_' . time() . '.txt';
            Storage::put($testFile, 'test');
            Storage::delete($testFile);
            return ['status' => 'success', 'message' => 'Storage yazma/okuma başarılı'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Storage hatası: ' . $e->getMessage()];
        }
    }

    /**
     * Cache'i kontrol et
     */
    private function checkCache(): array
    {
        try {
            $testKey = 'test_' . time();
            Cache::put($testKey, 'test', 60);
            $value = Cache::get($testKey);
            Cache::forget($testKey);
            
            if ($value === 'test') {
                return ['status' => 'success', 'message' => 'Cache yazma/okuma başarılı'];
            } else {
                return ['status' => 'warning', 'message' => 'Cache değer eşleşmiyor'];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Cache hatası: ' . $e->getMessage()];
        }
    }

    /**
     * Mail konfigürasyonunu kontrol et
     */
    private function checkMail(): array
    {
        $mailHost = config('mail.mailers.smtp.host');
        $mailPort = config('mail.mailers.smtp.port');
        
        if (empty($mailHost) || empty($mailPort)) {
            return ['status' => 'warning', 'message' => 'Mail ayarları eksik'];
        }
        
        return ['status' => 'success', 'message' => 'Mail ayarları yapılandırılmış'];
    }

    /**
     * Dosya izinlerini kontrol et
     */
    private function checkPermissions(): array
    {
        $directories = [
            storage_path(),
            storage_path('app'),
            storage_path('logs'),
            storage_path('framework'),
            base_path('bootstrap/cache'),
        ];

        $issues = [];
        foreach ($directories as $dir) {
            if (!is_writable($dir)) {
                $issues[] = $dir . ' yazılabilir değil';
            }
        }

        if (empty($issues)) {
            return ['status' => 'success', 'message' => 'Tüm dizinler yazılabilir'];
        } else {
            return ['status' => 'warning', 'message' => implode(', ', $issues)];
        }
    }

    /**
     * PHP uzantılarını kontrol et
     */
    private function checkPhpExtensions(): array
    {
        $requiredExtensions = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo'];
        $missingExtensions = [];
        
        foreach ($requiredExtensions as $extension) {
            if (!extension_loaded($extension)) {
                $missingExtensions[] = $extension;
            }
        }
        
        if (empty($missingExtensions)) {
            return ['status' => 'success', 'message' => 'Tüm gerekli PHP uzantıları yüklü'];
        } else {
            return ['status' => 'error', 'message' => 'Eksik uzantılar: ' . implode(', ', $missingExtensions)];
        }
    }

    /**
     * Laravel konfigürasyonunu kontrol et
     */
    private function checkLaravelConfig(): array
    {
        $issues = [];
        
        if (empty(config('app.key'))) {
            $issues[] = 'APP_KEY tanımlanmamış';
        }
        
        if (config('app.env') === 'production' && config('app.debug')) {
            $issues[] = 'Production ortamında debug modu açık';
        }
        
        if (empty($issues)) {
            return ['status' => 'success', 'message' => 'Laravel konfigürasyonu uygun'];
        } else {
            return ['status' => 'warning', 'message' => implode(', ', $issues)];
        }
    }

    /**
     * Bellek kullanımını al
     */
    private function getMemoryUsage(): string
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        
        if ($memoryLimit == -1) {
            return $this->formatBytes($memoryUsage) . ' / Sınırsız';
        }
        
        $memoryLimitBytes = $this->parseSize($memoryLimit);
        return $this->formatBytes($memoryUsage) . ' / ' . $memoryLimit;
    }

    /**
     * Bellek kullanımını kontrol et
     */
    private function checkMemoryUsage(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        
        if ($memoryLimit == -1) {
            return ['status' => 'ok', 'message' => 'Bellek sınırsız'];
        }
        
        $memoryLimitBytes = $this->parseSize($memoryLimit);
        $usagePercentage = ($memoryUsage / $memoryLimitBytes) * 100;
        
        if ($usagePercentage < 50) {
            return ['status' => 'ok', 'message' => 'Bellek kullanımı normal'];
        } elseif ($usagePercentage < 80) {
            return ['status' => 'warning', 'message' => 'Bellek kullanımı yüksek'];
        } else {
            return ['status' => 'error', 'message' => 'Bellek kullanımı kritik'];
        }
    }

    /**
     * Disk kullanımını kontrol et
     */
    private function checkDiskUsage(): array
    {
        $diskUsage = $this->getDiskUsage();
        
        if ($diskUsage['percentage'] < 70) {
            return ['status' => 'ok', 'message' => 'Disk kullanımı normal'];
        } elseif ($diskUsage['percentage'] < 90) {
            return ['status' => 'warning', 'message' => 'Disk kullanımı yüksek'];
        } else {
            return ['status' => 'error', 'message' => 'Disk kullanımı kritik'];
        }
    }

    /**
     * Cache boyutunu kontrol et
     */
    private function checkCacheSize(): array
    {
        $cacheSize = $this->getCacheSize();
        $cacheSizeBytes = $this->parseSize($cacheSize);
        
        if ($cacheSizeBytes < 50 * 1024 * 1024) { // 50MB
            return ['status' => 'ok', 'message' => 'Cache boyutu normal'];
        } elseif ($cacheSizeBytes < 200 * 1024 * 1024) { // 200MB
            return ['status' => 'warning', 'message' => 'Cache boyutu büyük'];
        } else {
            return ['status' => 'error', 'message' => 'Cache boyutu çok büyük'];
        }
    }

    /**
     * Log dosya boyutunu al
     */
    private function getLogSize(): string
    {
        try {
            $logDir = storage_path('logs');
            if (is_dir($logDir)) {
                $size = 0;
                foreach (File::allFiles($logDir) as $file) {
                    $size += $file->getSize();
                }
                return $this->formatBytes($size);
            }
            return '0 B';
        } catch (\Exception $e) {
            return 'Bilinmiyor';
        }
    }

    /**
     * Log dosya boyutunu kontrol et
     */
    private function checkLogSize(): array
    {
        $logSize = $this->getLogSize();
        $logSizeBytes = $this->parseSize($logSize);
        
        if ($logSizeBytes < 10 * 1024 * 1024) { // 10MB
            return ['status' => 'ok', 'message' => 'Log boyutu normal'];
        } elseif ($logSizeBytes < 100 * 1024 * 1024) { // 100MB
            return ['status' => 'warning', 'message' => 'Log boyutu büyük'];
        } else {
            return ['status' => 'error', 'message' => 'Log boyutu çok büyük'];
        }
    }

    /**
     * Genel sağlık mesajını al
     */
    private function getOverallHealthMessage(string $status, int $score): string
    {
        switch ($status) {
            case 'healthy':
                return "Sisteminiz mükemmel durumda! ({$score}/100)";
            case 'warning':
                return "Sisteminiz genel olarak iyi durumda, ancak bazı iyileştirmeler yapılabilir. ({$score}/100)";
            default:
                return "Sisteminizde kritik sorunlar var! Hemen dikkat edilmesi gereken alanlar bulunuyor. ({$score}/100)";
        }
    }

    /**
     * Önerileri al
     */
    private function getRecommendations(array $criticalChecks, array $performanceChecks, array $securityChecks): array
    {
        $recommendations = [];
        
        foreach (array_merge($criticalChecks, $performanceChecks, $securityChecks) as $check) {
            if ($check['status'] == 'warning') {
                switch ($check['name']) {
                    case 'Bellek Kullanımı':
                        $recommendations[] = 'PHP memory_limit değerini artırmayı düşünün';
                        break;
                    case 'Disk Kullanımı':
                        $recommendations[] = 'Eski dosyaları temizleyin ve disk alanını optimize edin';
                        break;
                    case 'Cache Boyutu':
                        $recommendations[] = 'Cache\'i düzenli olarak temizleyin';
                        break;
                    case 'Log Dosya Boyutu':
                        $recommendations[] = 'Eski log dosyalarını temizleyin';
                        break;
                }
            }
        }
        
        return array_unique($recommendations);
    }

    /**
     * Uyarıları al
     */
    private function getWarnings(array $criticalChecks, array $performanceChecks, array $securityChecks): array
    {
        $warnings = [];
        
        foreach (array_merge($criticalChecks, $performanceChecks, $securityChecks) as $check) {
            if ($check['status'] == 'error') {
                $warnings[] = $check['name'] . ': ' . $check['message'];
            }
        }
        
        return $warnings;
    }

    /**
     * Size string'ini byte'a çevir
     */
    private function parseSize(string $size): int
    {
        $units = ['B' => 1, 'K' => 1024, 'M' => 1024 * 1024, 'G' => 1024 * 1024 * 1024];
        $size = trim($size);
        $last = strtoupper($size[strlen($size) - 1]);
        $size = (float) $size;
        
        if (isset($units[$last])) {
            $size *= $units[$last];
        }
        
        return (int) $size;
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
