@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Log Yönetimi</h1>
                    <p class="text-muted mb-0">Sistem loglarını görüntüleyin, arayın ve yönetin</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('system.logs.stats') }}" class="btn btn-outline-info">
                        <i class="bi bi-graph-up me-2"></i>İstatistikler
                    </a>
                    <a href="{{ route('system.logs.live') }}" class="btn btn-outline-success">
                        <i class="bi bi-broadcast me-2"></i>Canlı Takip
                    </a>
                    <a href="{{ route('system.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Geri Dön
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Log Dosya Listesi -->
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-list-ul me-2"></i>Log Dosyaları
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            @if(count($logs) > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($logs as $log)
                                        <a href="?log={{ $log['name'] }}" 
                                           class="list-group-item list-group-item-action {{ $selectedLog == $log['name'] ? 'active' : '' }}">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $log['name'] }}</h6>
                                                    <small class="text-muted">Son değişiklik: {{ $log['modified'] }}</small>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-muted">{{ $log['size'] }}</small>
                                                    <div class="btn-group btn-group-sm mt-1" role="group">
                                                        <a href="{{ route('system.logs.download', $log['name']) }}" 
                                                           class="btn btn-outline-primary btn-sm" title="İndir">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                        <a href="{{ route('system.logs.export', $log['name']) }}" 
                                                           class="btn btn-outline-success btn-sm" title="CSV Export">
                                                            <i class="bi bi-file-earmark-spreadsheet"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-warning btn-sm" 
                                                                onclick="compressLog('{{ $log['name'] }}')" title="Sıkıştır">
                                                            <i class="bi bi-archive"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                onclick="deleteLog('{{ $log['name'] }}')" title="Sil">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-file-text text-muted mb-2" style="font-size: 2rem;"></i>
                                    <p class="text-muted mb-0">Log dosyası bulunamadı</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Log İşlemleri -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-tools me-2"></i>Log İşlemleri
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                        onclick="clearAllLogs()">
                                    <i class="bi bi-trash me-2"></i>Tüm Logları Sil
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" 
                                        data-bs-toggle="modal" data-bs-target="#clearOldModal">
                                    <i class="bi bi-clock-history me-2"></i>Eski Logları Temizle
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Log İçeriği -->
                <div class="col-lg-8 mb-4">
                    @if($selectedLog)
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-file-text me-2"></i>{{ $selectedLog }}
                                </h6>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('system.logs.download', $selectedLog) }}" 
                                       class="btn btn-outline-primary" title="İndir">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="deleteLog('{{ $selectedLog }}')" title="Sil">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Log Arama -->
                                <div class="mb-3">
                                    <form method="GET" action="{{ route('system.logs.search') }}" class="row g-2">
                                        <input type="hidden" name="log_file" value="{{ $selectedLog }}">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="query" 
                                                   value="{{ $query ?? '' }}" placeholder="Log içinde ara...">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bi bi-search me-2"></i>Ara
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Arama Sonuçları -->
                                @if(isset($results) && count($results) > 0)
                                    <div class="mb-3">
                                        <h6 class="text-success">{{ count($results) }} sonuç bulundu</h6>
                                    </div>
                                    <div class="log-content" style="max-height: 500px; overflow-y: auto;">
                                        @foreach($results as $result)
                                            <div class="log-entry mb-2 p-2 border rounded">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <small class="text-muted">Satır {{ $result['line'] }}</small>
                                                </div>
                                                <pre class="mb-0 small">{!! $result['highlighted'] !!}</pre>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Normal Log İçeriği -->
                                    <div class="log-content" style="max-height: 500px; overflow-y: auto; background: #f8f9fa; padding: 1rem; border-radius: 0.375rem;">
                                        <pre class="mb-0 small" style="white-space: pre-wrap; word-wrap: break-word;">{{ $logContent ?: 'Log dosyası okunamadı veya boş.' }}</pre>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-file-text text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-muted">Log Dosyası Seçin</h5>
                                <p class="text-muted">Görüntülemek istediğiniz log dosyasını sol panelden seçin.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tüm Logları Sil Modal -->
<div class="modal fade" id="clearAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tüm Logları Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tüm log dosyalarını silmek istediğinizden emin misiniz?</p>
                <p class="text-danger"><small>Bu işlem geri alınamaz!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form method="POST" action="{{ route('system.logs.clear-all') }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Tümünü Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Eski Logları Temizle Modal -->
<div class="modal fade" id="clearOldModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eski Logları Temizle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('system.logs.clear-old') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="days" class="form-label">Kaç günden eski logları silmek istiyorsunuz?</label>
                        <select class="form-select" id="days" name="days" required>
                            <option value="7">7 gün</option>
                            <option value="15">15 gün</option>
                            <option value="30" selected>30 gün</option>
                            <option value="60">60 gün</option>
                            <option value="90">90 gün</option>
                        </select>
                    </div>
                    <p class="text-warning"><small>Belirtilen süreden eski log dosyaları silinecektir.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-warning">Eski Logları Sil</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Log Silme Modal -->
<div class="modal fade" id="deleteLogModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log Dosyasını Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong id="logName"></strong> adlı log dosyasını silmek istediğinizden emin misiniz?</p>
                <p class="text-danger"><small>Bu işlem geri alınamaz!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="deleteLogForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteLog(logName) {
    document.getElementById('logName').textContent = logName;
    document.getElementById('deleteLogForm').action = `/system/logs/${logName}`;
    new bootstrap.Modal(document.getElementById('deleteLogModal')).show();
}

function clearAllLogs() {
    new bootstrap.Modal(document.getElementById('clearAllModal')).show();
}

function compressLog(logName) {
    if (confirm(`${logName} dosyasını sıkıştırmak istediğinizden emin misiniz?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/system/logs/compress/${logName}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<style>
.log-content {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    line-height: 1.4;
}

.log-entry {
    background-color: #fff;
    border-left: 3px solid #0d6efd;
}

.log-entry pre {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
}

.list-group-item.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

mark {
    background-color: #ffc107;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
}
</style>
@endpush
