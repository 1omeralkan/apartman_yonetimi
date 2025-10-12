@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Başlık Bölümü -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="animate-fade-in">
                    <h1 class="h3 mb-0 text-gradient">👥 Kullanıcı Yönetimi</h1>
                    <p class="text-muted mb-0">Sistem kullanıcılarını yönetin ve takip edin</p>
                </div>
                <div class="animate-slide-in-right">
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-lg shadow-sm hover-lift">
                        <i class="bi bi-plus-circle me-2"></i>Yeni Kullanıcı
                    </a>
                </div>
            </div>

            <!-- İstatistik Kartları -->
            <div class="row mb-4 animate-fade-in-up">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center">
                            <div class="icon-wrapper bg-primary bg-gradient rounded-circle mx-auto mb-3">
                                <i class="bi bi-people text-white"></i>
                            </div>
                            <h4 class="mb-1 text-primary">{{ $users->total() }}</h4>
                            <p class="text-muted mb-0">Toplam Kullanıcı</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center">
                            <div class="icon-wrapper bg-success bg-gradient rounded-circle mx-auto mb-3">
                                <i class="bi bi-person-check text-white"></i>
                            </div>
                            <h4 class="mb-1 text-success">{{ $users->where('is_active', true)->count() }}</h4>
                            <p class="text-muted mb-0">Aktif Kullanıcı</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center">
                            <div class="icon-wrapper bg-warning bg-gradient rounded-circle mx-auto mb-3">
                                <i class="bi bi-shield-check text-white"></i>
                            </div>
                            <h4 class="mb-1 text-warning">{{ $users->where('roles.name', 'super_admin')->count() }}</h4>
                            <p class="text-muted mb-0">Admin Kullanıcı</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center">
                            <div class="icon-wrapper bg-info bg-gradient rounded-circle mx-auto mb-3">
                                <i class="bi bi-clock-history text-white"></i>
                            </div>
                            <h4 class="mb-1 text-info">{{ $users->where('last_login_at', '>=', now()->subDays(7))->count() }}</h4>
                            <p class="text-muted mb-0">Son 7 Gün</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gelişmiş Filtreler -->
            <div class="card mb-4 border-0 shadow-sm animate-fade-in-up">
                <div class="card-header bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-funnel me-2"></i>Gelişmiş Filtreler
                        </h6>
                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>
                </div>
                <div class="collapse show" id="filterCollapse">
                    <div class="card-body">
                        <form method="GET" action="{{ route('users.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-search me-1"></i>Arama
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 focus-ring" name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Ad, soyad, email veya telefon ile ara...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-badge me-1"></i>Rol
                                </label>
                                <select class="form-select focus-ring" name="role">
                                    <option value="">Tüm Roller</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-toggle-on me-1"></i>Durum
                                </label>
                                <select class="form-select focus-ring" name="status">
                                    <option value="">Tüm Durumlar</option>
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2 shadow-sm hover-lift">
                                    <i class="bi bi-search me-1"></i>Ara
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary shadow-sm hover-lift" title="Filtreleri Temizle">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kullanıcı Listesi -->
            <div class="card border-0 shadow-sm animate-fade-in-up">
                <div class="card-header bg-white border-0 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>Kullanıcı Listesi
                        </h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2">{{ $users->total() }} Kullanıcı</span>
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
                                        <th class="border-0">
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input me-2" id="selectAll">
                                                Kullanıcı
                                            </div>
                                        </th>
                                        <th class="border-0">Email</th>
                                        <th class="border-0">Telefon</th>
                                        <th class="border-0">Roller</th>
                                        <th class="border-0">Durum</th>
                                        <th class="border-0">Son Giriş</th>
                                        <th class="border-0 text-center">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $index => $user)
                                        <tr class="animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <input type="checkbox" class="form-check-input me-3 user-checkbox" value="{{ $user->id }}">
                                                    <div class="avatar-sm me-3">
                                                        <div class="avatar-title bg-gradient-primary text-white rounded-circle shadow-sm">
                                                            {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                                        <small class="text-muted">
                                                            <i class="bi bi-hash me-1"></i>ID: {{ $user->id }}
                                                            @if($user->created_at)
                                                                • <i class="bi bi-calendar-plus me-1"></i>{{ $user->created_at->format('d.m.Y') }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $user->email }}</span>
                                                @if($user->email_verified_at)
                                                    <i class="bi bi-check-circle text-success ms-1" title="Email doğrulanmış"></i>
                                                @else
                                                    <i class="bi bi-exclamation-circle text-warning ms-1" title="Email doğrulanmamış"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->phone)
                                                    <span class="text-muted">{{ $user->phone }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->roles->count() > 0)
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-secondary me-1">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Rol atanmamış</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->last_login_at)
                                                    <span class="text-muted">{{ $user->last_login_at->diffForHumans() }}</span>
                                                @else
                                                    <span class="text-muted">Hiç giriş yapmamış</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm shadow-sm" role="group">
                                                    <a href="{{ route('users.show', $user) }}" 
                                                       class="btn btn-outline-info hover-lift" 
                                                       title="Detayları Görüntüle">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('users.edit', $user) }}" 
                                                       class="btn btn-outline-warning hover-lift" 
                                                       title="Kullanıcıyı Düzenle">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    @if($user->id !== auth()->id())
                                                        <button type="button" class="btn btn-outline-danger hover-lift" 
                                                                onclick="deleteUser({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')" 
                                                                title="Kullanıcıyı Sil">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-outline-secondary" 
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
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
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
/* Animasyonlar */
.animate-fade-in {
    animation: fadeIn 0.6s ease-in-out;
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out;
}

.animate-slide-in-right {
    animation: slideInRight 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Hover efektleri */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Gradient text */
.text-gradient {
    background: linear-gradient(45deg, #007bff, #6f42c1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Icon wrapper */
.icon-wrapper {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* Focus ring */
.focus-ring:focus {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    border-color: #86b7fe;
}

/* Avatar improvements */
.avatar-title {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Table improvements */
.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transform: scale(1.01);
}

/* Badge improvements */
.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.8em;
    border-radius: 0.5rem;
}

/* Button improvements */
.btn {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

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

/* Card improvements */
.card {
    border-radius: 1rem;
    overflow: hidden;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}

/* Input group improvements */
.input-group .form-control:focus {
    z-index: 3;
}

.input-group-text {
    border-radius: 0.5rem 0 0 0.5rem;
}

/* Loading animation */
.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table-responsive {
        border-radius: 0.5rem;
    }
    
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
