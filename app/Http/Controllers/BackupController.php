<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

class BackupController extends Controller
{
    /**
     * Yedekleme ana sayfası
     */
    public function index(): View
    {
        $backups = $this->getBackupList();
        $diskUsage = $this->getBackupDiskUsage();
        
        return view('system.backup', compact('backups', 'diskUsage'));
    }

    /**
     * Veritabanı yedeği oluştur
     */
    public function createDatabaseBackup(): RedirectResponse
    {
        try {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "database_backup_{$timestamp}.sql";
            
            // XAMPP için MySQL dump komutu
            $xamppMysqlPath = 'C:\xampp\mysql\bin\mysqldump';
            
            // XAMPP mysqldump yolunu kontrol et
            if (!file_exists($xamppMysqlPath)) {
                // Alternatif yollar dene
                $alternativePaths = [
                    'C:\xampp\mysql\bin\mysqldump.exe',
                    'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
                    'mysqldump' // Sistem PATH'inde varsa
                ];
                
                foreach ($alternativePaths as $path) {
                    if (file_exists($path) || $path === 'mysqldump') {
                        $xamppMysqlPath = $path;
                        break;
                    }
                }
            }
            
            // XAMPP için MySQL dump komutu (authentication plugin sorunu için)
            $command = sprintf(
                '"%s" --default-auth=mysql_native_password --user=%s --password=%s --host=%s %s > "%s"',
                $xamppMysqlPath,
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                storage_path("app/backups/{$filename}")
            );
            
            // Alternatif 2: Eğer mysqldump çalışmazsa Laravel ile yedekle
            if (!file_exists($xamppMysqlPath) && $xamppMysqlPath !== 'mysqldump') {
                // Laravel ile manuel yedekleme
                $this->createLaravelDatabaseBackup($filename);
                return redirect()->route('system.backup')->with('success', 'Veritabanı yedeği Laravel ile oluşturuldu.');
            }

            // Backup dizinini oluştur
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            // Komutu çalıştır
            exec($command . ' 2>&1', $output, $returnCode);
            
            // Debug için komutu logla
            \Log::info('MySQL Dump Command: ' . $command);
            \Log::info('MySQL Dump Output: ' . implode("\n", $output));
            \Log::info('MySQL Dump Return Code: ' . $returnCode);

            if ($returnCode === 0) {
                // Dosya boyutunu kontrol et
                $fileSize = File::size(storage_path("app/backups/{$filename}"));
                if ($fileSize > 0) {
                    return redirect()->route('system.backup')->with('success', 'Veritabanı yedeği başarıyla oluşturuldu. Boyut: ' . $this->formatBytes($fileSize));
                } else {
                    // MySQL dump çalışmadı, Laravel ile yedekle
                    \Log::info('MySQL dump failed, trying Laravel backup method');
                    $this->createLaravelDatabaseBackup($filename);
                    $fileSize = File::size(storage_path("app/backups/{$filename}"));
                    return redirect()->route('system.backup')->with('success', 'MySQL dump çalışmadı, Laravel ile yedekleme yapıldı. Boyut: ' . $this->formatBytes($fileSize));
                }
            } else {
                return redirect()->route('system.backup')->with('error', 'Veritabanı yedeği oluşturulurken hata oluştu. Hata: ' . implode("\n", $output) . ' Komut: ' . $command);
            }
        } catch (\Exception $e) {
            return redirect()->route('system.backup')->with('error', 'Yedekleme hatası: ' . $e->getMessage());
        }
    }

    /**
     * Dosya yedeği oluştur
     */
    public function createFileBackup(): RedirectResponse
    {
        try {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "files_backup_{$timestamp}.zip";
            
            // Backup dizinini oluştur
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            // Zip arşivi oluştur
            $zip = new \ZipArchive();
            $zipPath = storage_path("app/backups/{$filename}");
            
            if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Zip arşivi oluşturulamadı');
            }

            // Önemli dizinleri yedekle
            $directories = [
                'storage/app/public' => 'storage/app/public',
                'storage/logs' => 'storage/logs',
                'config' => 'config',
                'database/migrations' => 'database/migrations',
                'database/seeders' => 'database/seeders',
            ];

            foreach ($directories as $source => $destination) {
                $fullPath = base_path($source);
                if (File::exists($fullPath)) {
                    $this->addDirectoryToZip($zip, $fullPath, $destination);
                }
            }

            $zip->close();

            return redirect()->route('system.backup')->with('success', 'Dosya yedeği başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return redirect()->route('system.backup')->with('error', 'Dosya yedeği oluşturulurken hata: ' . $e->getMessage());
        }
    }

    /**
     * Tam sistem yedeği oluştur
     */
    public function createFullBackup(): RedirectResponse
    {
        try {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "full_backup_{$timestamp}.zip";
            
            // Backup dizinini oluştur
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            // Önce veritabanı yedeği oluştur
            $dbFilename = "database_backup_{$timestamp}.sql";
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                storage_path("app/backups/{$dbFilename}")
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new \Exception('Veritabanı yedeği oluşturulamadı');
            }

            // Zip arşivi oluştur
            $zip = new \ZipArchive();
            $zipPath = storage_path("app/backups/{$filename}");
            
            if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Zip arşivi oluşturulamadı');
            }

            // Veritabanı yedeğini ekle
            $zip->addFile(storage_path("app/backups/{$dbFilename}"), "database/{$dbFilename}");

            // Dosyaları ekle
            $directories = [
                'storage/app/public' => 'storage/app/public',
                'storage/logs' => 'storage/logs',
                'config' => 'config',
                'database/migrations' => 'database/migrations',
                'database/seeders' => 'database/seeders',
                'public/uploads' => 'public/uploads',
            ];

            foreach ($directories as $source => $destination) {
                $fullPath = base_path($source);
                if (File::exists($fullPath)) {
                    $this->addDirectoryToZip($zip, $fullPath, $destination);
                }
            }

            $zip->close();

            // Geçici veritabanı dosyasını sil
            File::delete(storage_path("app/backups/{$dbFilename}"));

            return redirect()->route('system.backup')->with('success', 'Tam sistem yedeği başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return redirect()->route('system.backup')->with('error', 'Tam yedekleme hatası: ' . $e->getMessage());
        }
    }

    /**
     * Yedek indir
     */
    public function download(string $filename)
    {
        $filePath = storage_path("app/backups/{$filename}");
        
        if (!File::exists($filePath)) {
            abort(404, 'Yedek dosyası bulunamadı');
        }

        return response()->download($filePath, $filename);
    }

    /**
     * Yedek sil
     */
    public function delete(string $filename): RedirectResponse
    {
        try {
            $filePath = storage_path("app/backups/{$filename}");
            
            if (!File::exists($filePath)) {
                return redirect()->route('system.backup')->with('error', 'Yedek dosyası bulunamadı');
            }

            File::delete($filePath);

            return redirect()->route('system.backup')->with('success', 'Yedek dosyası başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->route('system.backup')->with('error', 'Yedek silinirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Tüm yedekleri sil
     */
    public function deleteAll(): RedirectResponse
    {
        try {
            $backupDir = storage_path('app/backups');
            
            if (File::exists($backupDir)) {
                File::deleteDirectory($backupDir);
                File::makeDirectory($backupDir, 0755, true);
            }

            return redirect()->route('system.backup')->with('success', 'Tüm yedek dosyaları silindi.');
        } catch (\Exception $e) {
            return redirect()->route('system.backup')->with('error', 'Yedekler silinirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Yedek listesini getir
     */
    private function getBackupList(): array
    {
        $backupDir = storage_path('app/backups');
        $backups = [];

        if (File::exists($backupDir)) {
            $files = File::files($backupDir);
            
            foreach ($files as $file) {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'created_at' => date('d.m.Y H:i:s', $file->getMTime()),
                    'type' => $this->getBackupType($file->getFilename()),
                ];
            }

            // Tarihe göre sırala (en yeni önce)
            usort($backups, function($a, $b) {
                return strcmp($b['created_at'], $a['created_at']);
            });
        }

        return $backups;
    }

    /**
     * Yedek disk kullanımını hesapla
     */
    private function getBackupDiskUsage(): array
    {
        $backupDir = storage_path('app/backups');
        $totalSize = 0;

        if (File::exists($backupDir)) {
            $files = File::allFiles($backupDir);
            foreach ($files as $file) {
                $totalSize += $file->getSize();
            }
        }

        return [
            'total_files' => count($files ?? []),
            'total_size' => $this->formatBytes($totalSize),
        ];
    }

    /**
     * Yedek türünü belirle
     */
    private function getBackupType(string $filename): string
    {
        if (strpos($filename, 'database_') === 0) {
            return 'Veritabanı';
        } elseif (strpos($filename, 'files_') === 0) {
            return 'Dosyalar';
        } elseif (strpos($filename, 'full_') === 0) {
            return 'Tam Sistem';
        }
        return 'Bilinmiyor';
    }

    /**
     * Dizini zip'e ekle
     */
    private function addDirectoryToZip(\ZipArchive $zip, string $dirPath, string $zipPath): void
    {
        $files = File::allFiles($dirPath);
        
        foreach ($files as $file) {
            $relativePath = $zipPath . '/' . $file->getRelativePathname();
            $zip->addFile($file->getPathname(), $relativePath);
        }
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

    /**
     * Laravel ile manuel veritabanı yedeği oluştur
     */
    private function createLaravelDatabaseBackup(string $filename): void
    {
        $filePath = storage_path("app/backups/{$filename}");
        $handle = fopen($filePath, 'w');
        
        // SQL başlığı
        fwrite($handle, "-- Laravel Database Backup\n");
        fwrite($handle, "-- Generated: " . now() . "\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");
        
        // Tüm tabloları al
        $tables = DB::select('SHOW TABLES');
        $database = config('database.connections.mysql.database');
        
        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            
            // Tablo yapısını al
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $createStatement = $createTable[0]->{'Create Table'};
            
            fwrite($handle, "-- Table structure for table `{$tableName}`\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
            fwrite($handle, $createStatement . ";\n\n");
            
            // Tablo verilerini al
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                fwrite($handle, "-- Data for table `{$tableName}`\n");
                
                foreach ($rows as $row) {
                    $values = [];
                    foreach ($row as $key => $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = "'" . addslashes($value) . "'";
                        }
                    }
                    
                    $columns = implode('`, `', array_keys((array)$row));
                    $valuesStr = implode(', ', $values);
                    
                    fwrite($handle, "INSERT INTO `{$tableName}` (`{$columns}`) VALUES ({$valuesStr});\n");
                }
                fwrite($handle, "\n");
            }
        }
        
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }
}
