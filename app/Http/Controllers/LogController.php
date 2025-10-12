<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

class LogController extends Controller
{
    /**
     * Log ana sayfası
     */
    public function index(Request $request): View
    {
        $logDir = storage_path('logs');
        $logs = [];
        $selectedLog = $request->get('log');
        $logContent = '';
        $logLines = 100; // Varsayılan satır sayısı

        // Log dosyalarını listele
        if (File::exists($logDir)) {
            $files = File::files($logDir);
            
            foreach ($files as $file) {
                if ($file->getExtension() === 'log') {
                    $logs[] = [
                        'name' => $file->getFilename(),
                        'size' => $this->formatBytes($file->getSize()),
                        'modified' => date('d.m.Y H:i:s', $file->getMTime()),
                        'path' => $file->getPathname(),
                    ];
                }
            }

            // Tarihe göre sırala (en yeni önce)
            usort($logs, function($a, $b) {
                return strcmp($b['modified'], $a['modified']);
            });
        }

        // Seçili log dosyasının içeriğini getir
        if ($selectedLog && File::exists($logDir . '/' . $selectedLog)) {
            $logContent = $this->getLogContent($logDir . '/' . $selectedLog, $logLines);
        }

        return view('system.logs', compact('logs', 'selectedLog', 'logContent', 'logLines'));
    }

    /**
     * Log içeriğini getir
     */
    public function getLogContent(string $filePath, int $lines = 100): string
    {
        if (!File::exists($filePath)) {
            return 'Log dosyası bulunamadı.';
        }

        try {
            $file = new \SplFileObject($filePath);
            $file->seek(PHP_INT_MAX);
            $totalLines = $file->key() + 1;

            if ($totalLines <= $lines) {
                // Dosya küçükse tamamını oku
                return File::get($filePath);
            } else {
                // Son N satırı oku
                $file->seek($totalLines - $lines);
                $content = '';
                while (!$file->eof()) {
                    $content .= $file->current();
                    $file->next();
                }
                return $content;
            }
        } catch (\Exception $e) {
            return 'Log dosyası okunamadı: ' . $e->getMessage();
        }
    }

    /**
     * Log dosyasını indir
     */
    public function download(string $filename): Response
    {
        $filePath = storage_path("logs/{$filename}");
        
        if (!File::exists($filePath)) {
            abort(404, 'Log dosyası bulunamadı');
        }

        return response()->download($filePath, $filename);
    }

    /**
     * Log dosyasını sil
     */
    public function delete(string $filename): RedirectResponse
    {
        try {
            $filePath = storage_path("logs/{$filename}");
            
            if (!File::exists($filePath)) {
                return redirect()->route('system.logs')->with('error', 'Log dosyası bulunamadı');
            }

            File::delete($filePath);

            return redirect()->route('system.logs')->with('success', 'Log dosyası başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->route('system.logs')->with('error', 'Log silinirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Tüm log dosyalarını temizle
     */
    public function clearAll(): RedirectResponse
    {
        try {
            $logDir = storage_path('logs');
            $deletedCount = 0;

            if (File::exists($logDir)) {
                $files = File::files($logDir);
                
                foreach ($files as $file) {
                    if ($file->getExtension() === 'log') {
                        File::delete($file->getPathname());
                        $deletedCount++;
                    }
                }
            }

            return redirect()->route('system.logs')->with('success', "{$deletedCount} log dosyası silindi.");
        } catch (\Exception $e) {
            return redirect()->route('system.logs')->with('error', 'Loglar temizlenirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Eski log dosyalarını temizle
     */
    public function clearOld(Request $request): RedirectResponse
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        try {
            $days = $request->input('days');
            $logDir = storage_path('logs');
            $cutoffTime = time() - ($days * 24 * 60 * 60);
            $deletedCount = 0;

            if (File::exists($logDir)) {
                $files = File::files($logDir);
                
                foreach ($files as $file) {
                    if ($file->getExtension() === 'log' && $file->getMTime() < $cutoffTime) {
                        File::delete($file->getPathname());
                        $deletedCount++;
                    }
                }
            }

            return redirect()->route('system.logs')->with('success', "{$deletedCount} eski log dosyası silindi.");
        } catch (\Exception $e) {
            return redirect()->route('system.logs')->with('error', 'Eski loglar temizlenirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Log arama
     */
    public function search(Request $request): View
    {
        $query = $request->get('query');
        $logFile = $request->get('log_file');
        $results = [];

        if ($query && $logFile) {
            $logPath = storage_path("logs/{$logFile}");
            
            if (File::exists($logPath)) {
                $results = $this->searchInLog($logPath, $query);
            }
        }

        // Log dosyalarını listele
        $logDir = storage_path('logs');
        $logs = [];
        
        if (File::exists($logDir)) {
            $files = File::files($logDir);
            
            foreach ($files as $file) {
                if ($file->getExtension() === 'log') {
                    $logs[] = [
                        'name' => $file->getFilename(),
                        'size' => $this->formatBytes($file->getSize()),
                        'modified' => date('d.m.Y H:i:s', $file->getMTime()),
                    ];
                }
            }

            usort($logs, function($a, $b) {
                return strcmp($b['modified'], $a['modified']);
            });
        }

        return view('system.logs', compact('logs', 'results', 'query', 'logFile'));
    }

    /**
     * Log istatistikleri
     */
    public function stats(): View
    {
        $logDir = storage_path('logs');
        $stats = [
            'total_files' => 0,
            'total_size' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'info_count' => 0,
            'recent_errors' => [],
            'log_types' => []
        ];

        if (File::exists($logDir)) {
            $files = File::files($logDir);
            
            foreach ($files as $file) {
                if ($file->getExtension() === 'log') {
                    $stats['total_files']++;
                    $stats['total_size'] += $file->getSize();
                    
                    // Log türlerini say
                    $logType = $this->getLogType($file->getFilename());
                    $stats['log_types'][$logType] = ($stats['log_types'][$logType] ?? 0) + 1;
                }
            }
        }

        return view('system.log-stats', compact('stats'));
    }

    /**
     * Canlı log takibi
     */
    public function live(): View
    {
        return view('system.log-live');
    }

    /**
     * Canlı log verisi (AJAX)
     */
    public function liveData(Request $request): Response
    {
        $logFile = $request->get('log_file', 'laravel.log');
        $lastPosition = $request->get('position', 0);
        
        $logPath = storage_path("logs/{$logFile}");
        
        if (!File::exists($logPath)) {
            return response()->json(['error' => 'Log dosyası bulunamadı']);
        }

        $fileSize = File::size($logPath);
        
        if ($lastPosition >= $fileSize) {
            return response()->json(['new_lines' => [], 'position' => $fileSize]);
        }

        $handle = fopen($logPath, 'r');
        fseek($handle, $lastPosition);
        
        $newLines = [];
        while (($line = fgets($handle)) !== false) {
            $newLines[] = [
                'content' => trim($line),
                'timestamp' => $this->extractTimestamp($line),
                'level' => $this->extractLogLevel($line),
                'message' => $this->extractMessage($line)
            ];
        }
        
        $newPosition = ftell($handle);
        fclose($handle);

        return response()->json([
            'new_lines' => $newLines,
            'position' => $newPosition
        ]);
    }

    /**
     * Log dosyası türünü belirle
     */
    private function getLogType(string $filename): string
    {
        if (strpos($filename, 'laravel') !== false) {
            return 'Laravel';
        } elseif (strpos($filename, 'error') !== false) {
            return 'Error';
        } elseif (strpos($filename, 'access') !== false) {
            return 'Access';
        } elseif (strpos($filename, 'security') !== false) {
            return 'Security';
        }
        return 'Other';
    }

    /**
     * Log satırından timestamp çıkar
     */
    private function extractTimestamp(string $line): string
    {
        if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
            return $matches[1];
        }
        return '';
    }

    /**
     * Log satırından seviye çıkar
     */
    private function extractLogLevel(string $line): string
    {
        $levels = ['ERROR', 'WARNING', 'INFO', 'DEBUG', 'CRITICAL', 'ALERT', 'EMERGENCY'];
        
        foreach ($levels as $level) {
            if (strpos($line, ".{$level}:") !== false) {
                return $level;
            }
        }
        
        return 'INFO';
    }

    /**
     * Log satırından mesaj çıkar
     */
    private function extractMessage(string $line): string
    {
        if (preg_match('/\.(ERROR|WARNING|INFO|DEBUG|CRITICAL|ALERT|EMERGENCY):\s*(.+)/', $line, $matches)) {
            return trim($matches[2]);
        }
        return trim($line);
    }

    /**
     * Log dosyasını sıkıştır
     */
    public function compress(string $filename): RedirectResponse
    {
        try {
            $logPath = storage_path("logs/{$filename}");
            
            if (!File::exists($logPath)) {
                return redirect()->route('system.logs')->with('error', 'Log dosyası bulunamadı');
            }

            $compressedPath = $logPath . '.gz';
            
            if (file_exists($compressedPath)) {
                return redirect()->route('system.logs')->with('error', 'Sıkıştırılmış dosya zaten mevcut');
            }

            $data = File::get($logPath);
            $compressed = gzencode($data, 9);
            
            File::put($compressedPath, $compressed);
            File::delete($logPath);

            return redirect()->route('system.logs')->with('success', 'Log dosyası başarıyla sıkıştırıldı.');
        } catch (\Exception $e) {
            return redirect()->route('system.logs')->with('error', 'Sıkıştırma hatası: ' . $e->getMessage());
        }
    }

    /**
     * Log dosyasını dışa aktar (Excel/CSV)
     */
    public function export(string $filename): Response
    {
        try {
            $logPath = storage_path("logs/{$filename}");
            
            if (!File::exists($logPath)) {
                abort(404, 'Log dosyası bulunamadı');
            }

            $lines = File::lines($logPath);
            $csvData = "Timestamp,Level,Message\n";
            
            foreach ($lines as $line) {
                $timestamp = $this->extractTimestamp($line);
                $level = $this->extractLogLevel($line);
                $message = $this->extractMessage($line);
                
                $csvData .= "\"{$timestamp}\",\"{$level}\",\"" . str_replace('"', '""', $message) . "\"\n";
            }

            return response($csvData)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . pathinfo($filename, PATHINFO_FILENAME) . '.csv"');
        } catch (\Exception $e) {
            abort(500, 'Export hatası: ' . $e->getMessage());
        }
    }

    /**
     * Log dosyasında arama yap
     */
    private function searchInLog(string $filePath, string $query): array
    {
        $results = [];
        
        try {
            $handle = fopen($filePath, 'r');
            $lineNumber = 0;

            while (($line = fgets($handle)) !== false) {
                $lineNumber++;
                
                if (stripos($line, $query) !== false) {
                    $results[] = [
                        'line' => $lineNumber,
                        'content' => trim($line),
                        'highlighted' => $this->highlightSearch($line, $query),
                    ];
                }

                // Maksimum 100 sonuç
                if (count($results) >= 100) {
                    break;
                }
            }

            fclose($handle);
        } catch (\Exception $e) {
            // Hata durumunda boş dizi döndür
        }

        return $results;
    }

    /**
     * Arama terimini vurgula
     */
    private function highlightSearch(string $text, string $query): string
    {
        return preg_replace(
            '/(' . preg_quote($query, '/') . ')/i',
            '<mark>$1</mark>',
            htmlspecialchars($text)
        );
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
