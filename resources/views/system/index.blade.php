@extends('layouts.panel')

@section('content')
<div class="container-fluid px-3">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1 text-dark fw-bold">
                        <i class="bi bi-gear me-2 text-primary"></i>
                        Sistem Yönetimi
                    </h2>
                    <p class="text-muted mb-0 small">Sistem durumu, istatistikler ve hızlı erişim</p>
                </div>
                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('system.optimize') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-lightning me-1"></i>Sistemi Optimize Et
                        </button>
                    </form>
                    <a href="{{ route('system.health') }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-heart-pulse me-1"></i>Sağlık Kontrolü
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sistem Durumu Kartları -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-primary fw-bold mb-1 small">TOPLAM KULLANICI</h6>
                            <h4 class="mb-1 fw-bold text-dark">{{ $stats['total_users'] }}</h4>
                            <small class="text-muted">{{ $stats['active_users'] }} aktif</small>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-people text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-success fw-bold mb-1 small">TOPLAM SİTE</h6>
                            <h4 class="mb-1 fw-bold text-dark">{{ $stats['total_sites'] }}</h4>
                            <small class="text-muted">{{ $stats['total_apartments'] }} apartman</small>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-buildings text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-info fw-bold mb-1 small">TOPLAM DAİRE</h6>
                            <h4 class="mb-1 fw-bold text-dark">{{ $stats['total_flats'] }}</h4>
                            <small class="text-muted">{{ $stats['occupied_flats'] }} dolu</small>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-door-open text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-warning fw-bold mb-1 small">DOLULUK ORANI</h6>
                            <h4 class="mb-1 fw-bold text-dark">
                                {{ $stats['total_flats'] > 0 ? round(($stats['occupied_flats'] / $stats['total_flats']) * 100, 1) : 0 }}%
                            </h4>
                            <small class="text-muted">{{ $stats['occupied_flats'] }}/{{ $stats['total_flats'] }} daire</small>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-pie-chart text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Sistem Bilgileri -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i>Sistem Bilgileri
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                <span class="small text-muted">PHP</span>
                                <span class="badge bg-info">{{ $systemInfo['php_version'] }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                <span class="small text-muted">Laravel</span>
                                <span class="badge bg-success">{{ $systemInfo['laravel_version'] }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-2 bg-light rounded">
                                <small class="text-muted d-block">Sunucu Yazılımı</small>
                                <span class="small">{{ $systemInfo['server_software'] }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <small class="text-muted d-block">İşletim Sistemi</small>
                                <span class="small">{{ $systemInfo['server_os'] }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <small class="text-muted d-block">Bellek Limiti</small>
                                <span class="small">{{ $systemInfo['memory_limit'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disk Kullanımı -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-hdd me-2"></i>Disk Kullanımı
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">Kullanılan Alan</span>
                            <span class="small fw-bold">{{ $diskUsage['used'] }} / {{ $diskUsage['total'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $diskUsage['percentage'] }}%"
                                 aria-valuenow="{{ $diskUsage['percentage'] }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="badge bg-primary">{{ $diskUsage['percentage'] }}%</span>
                        </div>
                    </div>
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <div class="text-muted small">Toplam</div>
                                <div class="fw-bold small">{{ $diskUsage['total'] }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <div class="text-muted small">Kullanılan</div>
                                <div class="fw-bold small">{{ $diskUsage['used'] }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <div class="text-muted small">Boş</div>
                                <div class="fw-bold small">{{ $diskUsage['free'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Veritabanı Bilgileri -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-database me-2"></i>Veritabanı Bilgileri
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                <span class="small text-muted">Driver</span>
                                <span class="badge bg-primary">{{ $dbInfo['driver'] }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                <span class="small text-muted">Port</span>
                                <span class="small">{{ $dbInfo['connection']['port'] ?? '3306' }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-2 bg-light rounded">
                                <small class="text-muted d-block">Host</small>
                                <span class="small">{{ $dbInfo['connection']['host'] ?? 'localhost' }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <small class="text-muted d-block">Veritabanı</small>
                                <span class="small">{{ $dbInfo['connection']['database'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <small class="text-muted d-block">Charset</small>
                                <span class="small">{{ $dbInfo['connection']['charset'] ?? 'utf8' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hızlı Erişim -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-lightning me-2"></i>Hızlı Erişim
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('system.settings') }}" class="btn btn-outline-primary w-100 p-2">
                                <i class="bi bi-sliders d-block mb-1"></i>
                                <small>Sistem Ayarları</small>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('system.backup') }}" class="btn btn-outline-success w-100 p-2">
                                <i class="bi bi-archive d-block mb-1"></i>
                                <small>Yedekleme</small>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('system.logs') }}" class="btn btn-outline-info w-100 p-2">
                                <i class="bi bi-file-text d-block mb-1"></i>
                                <small>Loglar</small>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('system.cache') }}" class="btn btn-outline-warning w-100 p-2">
                                <i class="bi bi-speedometer2 d-block mb-1"></i>
                                <small>Cache</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Son Eklenen Kullanıcılar -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-person-plus me-2"></i>Son Eklenen Kullanıcılar
                    </h6>
                </div>
                <div class="card-body p-3">
                    @if($recentUsers->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentUsers as $user)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-primary fw-bold small">
                                                {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 small">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">
                                Tüm Kullanıcıları Görüntüle
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-person text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0 mt-2">Henüz kullanıcı eklenmemiş</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Son Eklenen Siteler -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-building me-2"></i>Son Eklenen Siteler
                    </h6>
                </div>
                <div class="card-body p-3">
                    @if($recentSites->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentSites as $site)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <div>
                                        <h6 class="mb-0 small">{{ $site->name }}</h6>
                                        <small class="text-muted">{{ $site->address }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $site->status == 'active' ? 'success' : 'secondary' }} small">
                                            {{ $site->status == 'active' ? 'Aktif' : 'Pasif' }}
                                        </span>
                                        <small class="text-muted d-block">{{ $site->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('sites.index') }}" class="btn btn-sm btn-outline-primary">
                                Tüm Siteleri Görüntüle
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-building text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0 mt-2">Henüz site eklenmemiş</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.progress-bar {
    transition: width 0.6s ease;
}

.list-group-item {
    transition: all 0.3s ease;
}

.list-group-item:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.badge {
    font-size: 0.75rem;
}

.bg-opacity-10 {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}
</style>
@endsection
