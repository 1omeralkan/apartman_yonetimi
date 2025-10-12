@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Sistem Yönetimi</h1>
                    <p class="text-muted mb-0">Sistem durumu, istatistikler ve hızlı erişim</p>
                </div>
                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('system.optimize') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-lightning me-2"></i>Sistemi Optimize Et
                        </button>
                    </form>
                    <a href="{{ route('system.health') }}" class="btn btn-outline-info">
                        <i class="bi bi-heart-pulse me-2"></i>Sağlık Kontrolü
                    </a>
                </div>
            </div>

            <!-- Sistem Durumu Kartları -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Toplam Kullanıcı</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                                    <small class="text-muted">{{ $stats['active_users'] }} aktif</small>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-people fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Toplam Site</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_sites'] }}</div>
                                    <small class="text-muted">{{ $stats['total_apartments'] }} apartman</small>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-buildings fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Toplam Daire</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_flats'] }}</div>
                                    <small class="text-muted">{{ $stats['occupied_flats'] }} dolu</small>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-door-open fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Doluluk Oranı</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $stats['total_flats'] > 0 ? round(($stats['occupied_flats'] / $stats['total_flats']) * 100, 1) : 0 }}%
                                    </div>
                                    <small class="text-muted">{{ $stats['occupied_flats'] }}/{{ $stats['total_flats'] }} daire</small>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-pie-chart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Sistem Bilgileri -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="bi bi-info-circle me-2"></i>Sistem Bilgileri
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold">PHP Versiyonu</td>
                                            <td><span class="badge bg-info">{{ $systemInfo['php_version'] }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Laravel Versiyonu</td>
                                            <td><span class="badge bg-success">{{ $systemInfo['laravel_version'] }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Sunucu Yazılımı</td>
                                            <td>{{ $systemInfo['server_software'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">İşletim Sistemi</td>
                                            <td>{{ $systemInfo['server_os'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Bellek Limiti</td>
                                            <td>{{ $systemInfo['memory_limit'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Max Çalışma Süresi</td>
                                            <td>{{ $systemInfo['max_execution_time'] }} saniye</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disk Kullanımı -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="bi bi-hdd me-2"></i>Disk Kullanımı
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Kullanılan Alan</span>
                                    <span>{{ $diskUsage['used'] }} / {{ $diskUsage['total'] }}</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $diskUsage['percentage'] }}%"
                                         aria-valuenow="{{ $diskUsage['percentage'] }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        {{ $diskUsage['percentage'] }}%
                                    </div>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="text-muted small">Toplam</div>
                                    <div class="fw-semibold">{{ $diskUsage['total'] }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted small">Kullanılan</div>
                                    <div class="fw-semibold">{{ $diskUsage['used'] }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted small">Boş</div>
                                    <div class="fw-semibold">{{ $diskUsage['free'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Veritabanı Bilgileri -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="bi bi-database me-2"></i>Veritabanı Bilgileri
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold">Driver</td>
                                            <td><span class="badge bg-primary">{{ $dbInfo['driver'] }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Host</td>
                                            <td>{{ $dbInfo['connection']['host'] ?? 'localhost' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Port</td>
                                            <td>{{ $dbInfo['connection']['port'] ?? '3306' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Veritabanı</td>
                                            <td>{{ $dbInfo['connection']['database'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Charset</td>
                                            <td>{{ $dbInfo['connection']['charset'] ?? 'utf8' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hızlı Erişim -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="bi bi-lightning me-2"></i>Hızlı Erişim
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="{{ route('system.settings') }}" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-sliders d-block mb-1"></i>
                                        <small>Sistem Ayarları</small>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('system.backup') }}" class="btn btn-outline-success w-100">
                                        <i class="bi bi-archive d-block mb-1"></i>
                                        <small>Yedekleme</small>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('system.logs') }}" class="btn btn-outline-info w-100">
                                        <i class="bi bi-file-text d-block mb-1"></i>
                                        <small>Loglar</small>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('system.cache') }}" class="btn btn-outline-warning w-100">
                                        <i class="bi bi-speedometer2 d-block mb-1"></i>
                                        <small>Cache</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Son Eklenen Kullanıcılar -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="bi bi-person-plus me-2"></i>Son Eklenen Kullanıcılar
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($recentUsers->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentUsers as $user)
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                        {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->first_name }} {{ $user->last_name }}</h6>
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
                                <p class="text-muted text-center">Henüz kullanıcı eklenmemiş</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Son Eklenen Siteler -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="bi bi-building me-2"></i>Son Eklenen Siteler
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($recentSites->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentSites as $site)
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div>
                                                <h6 class="mb-0">{{ $site->name }}</h6>
                                                <small class="text-muted">{{ $site->address }}</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-{{ $site->status == 'active' ? 'success' : 'secondary' }}">
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
                                <p class="text-muted text-center">Henüz site eklenmemiş</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}
.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
}
</style>
@endsection
