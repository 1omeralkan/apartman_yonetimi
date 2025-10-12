@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Cache Yönetimi</h1>
                    <p class="text-muted mb-0">Sistem cache'ini yönetin ve optimize edin</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('system.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Geri Dön
                    </a>
                </div>
            </div>

            <!-- Cache İstatistikleri -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-primary">Toplam Cache</h6>
                                    <h4 class="mb-0" id="totalCache">{{ $cacheStats['total'] ?? '0' }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-speedometer2 text-primary" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-success">Aktif Cache</h6>
                                    <h4 class="mb-0" id="activeCache">{{ $cacheStats['active'] ?? '0' }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-info">Cache Boyutu</h6>
                                    <h4 class="mb-0" id="cacheSize">{{ $cacheStats['size'] ?? '0 MB' }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-hdd text-info" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-warning">Son Temizlik</h6>
                                    <h4 class="mb-0" id="lastClean">{{ $cacheStats['last_clean'] ?? 'Bilinmiyor' }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Cache İşlemleri -->
                <div class="col-lg-4 mb-4">
                    <!-- Hızlı İşlemler -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lightning me-2"></i>Hızlı İşlemler
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary" onclick="clearCache('all')">
                                    <i class="bi bi-trash me-2"></i>Tüm Cache'i Temizle
                                </button>
                                <button type="button" class="btn btn-success" onclick="rebuildCache()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Cache'i Yeniden Oluştur
                                </button>
                                <button type="button" class="btn btn-info" onclick="optimizeCache()">
                                    <i class="bi bi-gear me-2"></i>Cache'i Optimize Et
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Cache Türleri -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-list me-2"></i>Cache Türleri
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                        onclick="clearCache('config')">
                                    <i class="bi bi-gear me-2"></i>Config Cache
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                        onclick="clearCache('route')">
                                    <i class="bi bi-signpost me-2"></i>Route Cache
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                        onclick="clearCache('view')">
                                    <i class="bi bi-eye me-2"></i>View Cache
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                        onclick="clearCache('application')">
                                    <i class="bi bi-app me-2"></i>Application Cache
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Cache Ayarları -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-sliders me-2"></i>Cache Ayarları
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Cache Driver</label>
                                <input type="text" class="form-control" value="{{ config('cache.default') }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cache Prefix</label>
                                <input type="text" class="form-control" value="{{ config('cache.prefix') }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">TTL (Saniye)</label>
                                <input type="text" class="form-control" value="{{ config('cache.ttl') ?? '3600' }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cache Anahtarları -->
                <div class="col-lg-8 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-key me-2"></i>Cache Anahtarları
                            </h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshCacheKeys()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Yenile
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearAllKeys()">
                                    <i class="bi bi-trash me-1"></i>Tümünü Sil
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Cache Anahtarı Arama -->
                            <div class="mb-3">
                                <div class="row g-2">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="searchKey" 
                                               placeholder="Cache anahtarı ara...">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary w-100" onclick="searchCacheKey()">
                                            <i class="bi bi-search me-2"></i>Ara
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Cache Anahtarları Listesi -->
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-hover">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th>Anahtar</th>
                                            <th>Değer</th>
                                            <th>TTL</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cacheKeysTable">
                                        @if(count($cacheKeys) > 0)
                                            @foreach($cacheKeys as $cacheItem)
                                                <tr>
                                                    <td>
                                                        <code class="small">{{ $cacheItem['key'] ?? 'Bilinmiyor' }}</code>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted small">
                                                            {{ $cacheItem['size'] ?? '0 B' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            {{ $cacheItem['expires'] ?? $cacheItem['modified'] ?? 'Aktif' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                                    onclick="viewCacheValue('{{ $cacheItem['key'] ?? '' }}')" title="Görüntüle">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                    onclick="deleteCacheKey('{{ $cacheItem['key'] ?? '' }}')" title="Sil">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                                    <p class="mb-0 mt-2">Cache anahtarı bulunamadı</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cache Değeri Görüntüleme Modal -->
<div class="modal fade" id="viewCacheModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cache Değeri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Anahtar:</label>
                    <code id="modalCacheKey"></code>
                </div>
                <div class="mb-3">
                    <label class="form-label">Değer:</label>
                    <pre id="modalCacheValue" class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;"></pre>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-danger" id="modalDeleteBtn">Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- Cache Anahtarı Silme Modal -->
<div class="modal fade" id="deleteKeyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cache Anahtarını Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong id="deleteKeyName"></strong> anahtarını silmek istediğinizden emin misiniz?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="deleteKeyForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tüm Cache Anahtarlarını Sil Modal -->
<div class="modal fade" id="clearAllKeysModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tüm Cache Anahtarlarını Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tüm cache anahtarlarını silmek istediğinizden emin misiniz?</p>
                <p class="text-danger"><small>Bu işlem geri alınamaz!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form method="POST" action="{{ route('system.cache.clear') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Tümünü Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function clearCache(type) {
    const types = {
        'all': 'Tüm Cache',
        'config': 'Config Cache',
        'route': 'Route Cache',
        'view': 'View Cache',
        'application': 'Application Cache'
    };
    
    if (confirm(`${types[type]} temizlemek istediğinizden emin misiniz?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("system.cache.clear") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = type;
        
        form.appendChild(csrfToken);
        form.appendChild(typeInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function rebuildCache() {
    if (confirm('Cache\'i yeniden oluşturmak istediğinizden emin misiniz?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("system.cache.rebuild") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function optimizeCache() {
    if (confirm('Cache\'i optimize etmek istediğinizden emin misiniz?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("system.cache.optimize") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function viewCacheValue(key) {
    document.getElementById('modalCacheKey').textContent = key;
    document.getElementById('modalCacheValue').textContent = 'Yükleniyor...';
    
    // Modal delete button action
    const modalDeleteBtn = document.getElementById('modalDeleteBtn');
    modalDeleteBtn.onclick = function() {
        deleteCacheKey(key);
        bootstrap.Modal.getInstance(document.getElementById('viewCacheModal')).hide();
    };
    
    new bootstrap.Modal(document.getElementById('viewCacheModal')).show();
    
    // Simulate loading cache value (in real implementation, this would be an AJAX call)
    setTimeout(() => {
        document.getElementById('modalCacheValue').textContent = 'Cache değeri burada görüntülenecek...';
    }, 500);
}

function deleteCacheKey(key) {
    document.getElementById('deleteKeyName').textContent = key;
    document.getElementById('deleteKeyForm').action = '{{ route("system.cache.forget-key") }}';
    
    const form = document.getElementById('deleteKeyForm');
    form.innerHTML = `
        @csrf
        <input type="hidden" name="key" value="${key}">
        <button type="submit" class="btn btn-danger">Sil</button>
    `;
    
    new bootstrap.Modal(document.getElementById('deleteKeyModal')).show();
}

function clearAllKeys() {
    new bootstrap.Modal(document.getElementById('clearAllKeysModal')).show();
}

function refreshCacheKeys() {
    location.reload();
}

function searchCacheKey() {
    const searchTerm = document.getElementById('searchKey').value.toLowerCase();
    const rows = document.querySelectorAll('#cacheKeysTable tr');
    
    rows.forEach(row => {
        const keyCell = row.querySelector('code');
        if (keyCell && keyCell.textContent.toLowerCase().includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Auto refresh cache stats every 30 seconds
setInterval(function() {
    // In real implementation, this would fetch updated stats via AJAX
    console.log('Cache stats refreshed');
}, 30000);
</script>

<style>
.sticky-top {
    position: sticky;
    top: 0;
    z-index: 1020;
}

code {
    background-color: #f8f9fa;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

pre {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    line-height: 1.4;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transition: box-shadow 0.15s ease-in-out;
}
</style>
@endpush
