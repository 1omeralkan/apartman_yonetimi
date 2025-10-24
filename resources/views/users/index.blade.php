@extends('layouts.panel')

@section('content')
<div class="container-fluid px-3">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1 text-dark fw-bold">
                        <i class="bi bi-people me-2 text-primary"></i>
                        Kullanıcı Yönetimi
                    </h2>
                    <p class="text-muted mb-0 small">Sistem kullanıcılarını yönetin ve takip edin</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Yeni Kullanıcı
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- İstatistik Kartları -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-primary fw-bold mb-1 small">TOPLAM KULLANICI</h6>
                            <h4 class="mb-1 fw-bold text-dark">{{ $users->total() }}</h4>
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
                            <h6 class="text-success fw-bold mb-1 small">AKTİF KULLANICI</h6>
                            <h4 class="mb-1 fw-bold text-dark">{{ $users->where('is_active', true)->count() }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-person-check text-success" style="font-size: 1.5rem;"></i>
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
                            <h6 class="text-warning fw-bold mb-1 small">ADMİN KULLANICI</h6>
                            <h4 class="mb-1 fw-bold text-dark">{{ $users->where('roles.name', 'super_admin')->count() }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-shield-check text-warning" style="font-size: 1.5rem;"></i>
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
                            <h6 class="text-info fw-bold mb-1 small">SON 7 GÜN</h6>
                            <h4 class="mb-1 fw-bold text-dark">{{ $users->where('last_login_at', '>=', now()->subDays(7))->count() }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-clock-history text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gelişmiş Filtreler -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-funnel me-2"></i>Gelişmiş Filtreler
                </h6>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('users.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">
                            <i class="bi bi-search me-1"></i>Arama
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Ad, soyad, email veya telefon ile ara...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">
                            <i class="bi bi-person-badge me-1"></i>Rol
                        </label>
                        <select class="form-select" name="role">
                            <option value="">Tüm Roller</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">
                            <i class="bi bi-toggle-on me-1"></i>Durum
                        </label>
                        <select class="form-select" name="status">
                            <option value="">Tüm Durumlar</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2 btn-sm">
                            <i class="bi bi-search me-1"></i>Ara
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm" title="Filtreleri Temizle">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Kullanıcı Listesi -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-list-ul me-2"></i>Kullanıcı Listesi
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary">{{ $users->total() }} Kullanıcı</span>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary" title="Liste Görünümü">
                            <i class="bi bi-list"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" title="Kart Görünümü">
                            <i class="bi bi-grid"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 p-3">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input me-2" id="selectAll">
                                        <span class="small fw-bold">Kullanıcı</span>
                                    </div>
                                </th>
                                <th class="border-0 p-3">
                                    <span class="small fw-bold">Email</span>
                                </th>
                                <th class="border-0 p-3">
                                    <span class="small fw-bold">Telefon</span>
                                </th>
                                <th class="border-0 p-3">
                                    <span class="small fw-bold">Roller</span>
                                </th>
                                <th class="border-0 p-3">
                                    <span class="small fw-bold">Durum</span>
                                </th>
                                <th class="border-0 p-3">
                                    <span class="small fw-bold">Son Giriş</span>
                                </th>
                                <th class="border-0 p-3 text-center">
                                    <span class="small fw-bold">İşlemler</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                                <tr>
                                    <td class="p-3">
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" class="form-check-input me-3 user-checkbox" value="{{ $user->id }}">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <span class="text-primary fw-bold small">
                                                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 small fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-hash me-1"></i>ID: {{ $user->id }}
                                                    @if($user->created_at)
                                                        • <i class="bi bi-calendar-plus me-1"></i>{{ $user->created_at->format('d.m.Y') }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <span class="small">{{ $user->email }}</span>
                                        @if($user->email_verified_at)
                                            <i class="bi bi-check-circle text-success ms-1" title="Email doğrulanmış"></i>
                                        @else
                                            <i class="bi bi-exclamation-circle text-warning ms-1" title="Email doğrulanmamış"></i>
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        @if($user->phone)
                                            <span class="small">{{ $user->phone }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        @if($user->roles->count() > 0)
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-secondary me-1 small">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted small">Rol atanmamış</span>
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        @if($user->is_active)
                                            <span class="badge bg-success small">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary small">Pasif</span>
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        @if($user->last_login_at)
                                            <span class="text-muted small">{{ $user->last_login_at->diffForHumans() }}</span>
                                        @else
                                            <span class="text-muted small">Hiç giriş yapmamış</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('users.show', $user) }}" 
                                               class="btn btn-outline-info btn-sm" 
                                               title="Detayları Görüntüle">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $user) }}" 
                                               class="btn btn-outline-warning btn-sm" 
                                               title="Kullanıcıyı Düzenle">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                        onclick="deleteUser({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')" 
                                                        title="Kullanıcıyı Sil">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                        title="Kendi hesabınızı silemezsiniz" disabled>
                                                    <i class="bi bi-shield-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Sayfalama -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted small">
                        Toplam {{ $users->total() }} kullanıcıdan {{ $users->firstItem() }}-{{ $users->lastItem() }} arası gösteriliyor
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">Kullanıcı bulunamadı</h5>
                    <p class="text-muted">Henüz hiç kullanıcı eklenmemiş veya arama kriterlerinize uygun kullanıcı yok.</p>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>İlk Kullanıcıyı Ekle
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Silme Onay Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kullanıcı Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong id="userName"></strong> adlı kullanıcıyı silmek istediğinizden emin misiniz?</p>
                <p class="text-danger"><small>Bu işlem geri alınamaz!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
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
function deleteUser(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = `/users/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Select All functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Individual checkbox change
document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allCheckboxes = document.querySelectorAll('.user-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        const selectAllCheckbox = document.getElementById('selectAll');
        
        selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
    });
});

// Auto-refresh stats every 30 seconds
setInterval(function() {
    // Bu kısım AJAX ile istatistikleri güncelleyebilir
    console.log('Stats refreshed');
}, 30000);
</script>
@endpush

@push('styles')
<style>
/* Card hover effects */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Button hover effects */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Table row hover */
.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Badge improvements */
.badge {
    font-size: 0.75rem;
}

/* Avatar improvements */
.bg-opacity-10 {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}

/* Button group improvements */
.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.5rem;
    border-bottom-left-radius: 0.5rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}

/* Input group improvements */
.input-group .form-control:focus {
    z-index: 3;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.5rem !important;
        margin-bottom: 0.25rem;
    }
}
</style>
@endpush
