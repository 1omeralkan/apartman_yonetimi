@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-heart-pulse text-danger me-2"></i>Sistem Sağlık Kontrolü
                    </h1>
                    <p class="text-muted mb-0">Sisteminizin genel sağlığını ve performansını kontrol edin</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="refreshHealthCheck()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Yenile
                    </button>
                    <a href="{{ route('system.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Geri Dön
                    </a>
                </div>
            </div>

            <!-- Genel Sağlık Durumu -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-{{ $overallHealth['status'] == 'healthy' ? 'success' : ($overallHealth['status'] == 'warning' ? 'warning' : 'danger') }}">
                        <div class="card-header bg-{{ $overallHealth['status'] == 'healthy' ? 'success' : ($overallHealth['status'] == 'warning' ? 'warning' : 'danger') }} text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-{{ $overallHealth['status'] == 'healthy' ? 'check-circle' : ($overallHealth['status'] == 'warning' ? 'exclamation-triangle' : 'x-circle') }} me-2"></i>
                                    Genel Sağlık Durumu
                                </h5>
                                <span class="badge bg-light text-dark fs-6">
                                    {{ $overallHealth['score'] }}/100
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="mb-3">{{ $overallHealth['message'] }}</p>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $overallHealth['status'] == 'healthy' ? 'success' : ($overallHealth['status'] == 'warning' ? 'warning' : 'danger') }}" 
                                             style="width: {{ $overallHealth['score'] }}%"></div>
                                    </div>
                                    <small class="text-muted">Son kontrol: {{ $overallHealth['last_check'] }}</small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="mb-2">
                                            <span class="badge bg-success me-1">{{ $overallHealth['healthy_count'] }} Sağlıklı</span>
                                            <span class="badge bg-warning me-1">{{ $overallHealth['warning_count'] }} Uyarı</span>
                                            <span class="badge bg-danger">{{ $overallHealth['error_count'] }} Hata</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Kritik Sistem Kontrolleri -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-gear me-2"></i>Kritik Sistem Kontrolleri
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($criticalChecks as $check)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded border-start border-{{ $check['status'] == 'ok' ? 'success' : ($check['status'] == 'warning' ? 'warning' : 'danger') }} border-4">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="bi bi-{{ $check['status'] == 'ok' ? 'check-circle text-success' : ($check['status'] == 'warning' ? 'exclamation-triangle text-warning' : 'x-circle text-danger') }} me-2"></i>
                                            <h6 class="mb-0">{{ $check['name'] }}</h6>
                                        </div>
                                        <p class="text-muted small mb-0">{{ $check['description'] }}</p>
                                        @if($check['status'] != 'ok')
                                            <small class="text-{{ $check['status'] == 'warning' ? 'warning' : 'danger' }}">
                                                {{ $check['message'] }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $check['status'] == 'ok' ? 'success' : ($check['status'] == 'warning' ? 'warning' : 'danger') }}">
                                            {{ $check['status'] == 'ok' ? 'OK' : ($check['status'] == 'warning' ? 'Uyarı' : 'Hata') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Performans Kontrolleri -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-speedometer2 me-2"></i>Performans Kontrolleri
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($performanceChecks as $check)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded border-start border-{{ $check['status'] == 'ok' ? 'success' : ($check['status'] == 'warning' ? 'warning' : 'danger') }} border-4">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="bi bi-{{ $check['status'] == 'ok' ? 'check-circle text-success' : ($check['status'] == 'warning' ? 'exclamation-triangle text-warning' : 'x-circle text-danger') }} me-2"></i>
                                            <h6 class="mb-0">{{ $check['name'] }}</h6>
                                        </div>
                                        <p class="text-muted small mb-0">{{ $check['value'] }}</p>
                                        @if($check['status'] != 'ok')
                                            <small class="text-{{ $check['status'] == 'warning' ? 'warning' : 'danger' }}">
                                                {{ $check['message'] }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $check['status'] == 'ok' ? 'success' : ($check['status'] == 'warning' ? 'warning' : 'danger') }}">
                                            {{ $check['status'] == 'ok' ? 'İyi' : ($check['status'] == 'warning' ? 'Orta' : 'Kötü') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Güvenlik Kontrolleri -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-shield-check me-2"></i>Güvenlik Kontrolleri
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($securityChecks as $check)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded border-start border-{{ $check['status'] == 'ok' ? 'success' : ($check['status'] == 'warning' ? 'warning' : 'danger') }} border-4">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="bi bi-{{ $check['status'] == 'ok' ? 'check-circle text-success' : ($check['status'] == 'warning' ? 'exclamation-triangle text-warning' : 'x-circle text-danger') }} me-2"></i>
                                            <h6 class="mb-0">{{ $check['name'] }}</h6>
                                        </div>
                                        <p class="text-muted small mb-0">{{ $check['description'] }}</p>
                                        @if($check['status'] != 'ok')
                                            <small class="text-{{ $check['status'] == 'warning' ? 'warning' : 'danger' }}">
                                                {{ $check['message'] }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $check['status'] == 'ok' ? 'success' : ($check['status'] == 'warning' ? 'warning' : 'danger') }}">
                                            {{ $check['status'] == 'ok' ? 'Güvenli' : ($check['status'] == 'warning' ? 'Risk' : 'Tehlike') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sistem Bilgileri -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>Sistem Bilgileri
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($systemInfo as $info)
                                <div class="d-flex justify-content-between align-items-center mb-2 py-2 border-bottom">
                                    <div>
                                        <strong>{{ $info['label'] }}:</strong>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-secondary">{{ $info['value'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Öneriler ve Uyarılar -->
            @if(count($recommendations) > 0 || count($warnings) > 0)
                <div class="row">
                    @if(count($recommendations) > 0)
                        <div class="col-lg-6 mb-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-lightbulb me-2"></i>Öneriler
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @foreach($recommendations as $recommendation)
                                        <div class="alert alert-info alert-dismissible fade show mb-2" role="alert">
                                            <i class="bi bi-info-circle me-2"></i>
                                            {{ $recommendation }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(count($warnings) > 0)
                        <div class="col-lg-6 mb-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Uyarılar
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @foreach($warnings as $warning)
                                        <div class="alert alert-warning alert-dismissible fade show mb-2" role="alert">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            {{ $warning }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Hızlı İşlemler -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-tools me-2"></i>Hızlı İşlemler
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <button type="button" class="btn btn-outline-primary w-100" onclick="clearCache()">
                                        <i class="bi bi-trash me-2"></i>Cache Temizle
                                    </button>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <button type="button" class="btn btn-outline-success w-100" onclick="optimizeSystem()">
                                        <i class="bi bi-gear me-2"></i>Sistemi Optimize Et
                                    </button>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <button type="button" class="btn btn-outline-info w-100" onclick="generateReport()">
                                        <i class="bi bi-file-text me-2"></i>Rapor Oluştur
                                    </button>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <button type="button" class="btn btn-outline-warning w-100" onclick="refreshHealthCheck()">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Kontrolü Yenile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function refreshHealthCheck() {
    location.reload();
}

function clearCache() {
    if (confirm('Cache\'i temizlemek istediğinizden emin misiniz?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("system.cache.clear") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function optimizeSystem() {
    if (confirm('Sistemi optimize etmek istediğinizden emin misiniz?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("system.optimize") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function generateReport() {
    // Rapor oluşturma işlemi (gelecekte implement edilecek)
    alert('Rapor oluşturma özelliği yakında eklenecek!');
}

// Otomatik yenileme (5 dakikada bir)
setInterval(function() {
    // İsteğe bağlı: otomatik yenileme
    // location.reload();
}, 300000);
</script>

<style>
.border-4 {
    border-width: 4px !important;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transition: box-shadow 0.15s ease-in-out;
}

.progress {
    background-color: #e9ecef;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush
