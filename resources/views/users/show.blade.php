@extends('layouts.panel')

@section('content')
<div class="container-fluid px-3">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1 text-dark fw-bold">
                        <i class="bi bi-person me-2 text-primary"></i>
                        Kullanıcı Detayı
                    </h2>
                    <p class="text-muted mb-0 small">{{ $user->first_name }} {{ $user->last_name }} kullanıcısının detaylı bilgileri</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Düzenle
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Geri Dön
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Ana Bilgiler -->
        <div class="col-lg-8">
            <!-- Kişisel Bilgiler -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-person me-2"></i>Kişisel Bilgiler
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-2 bg-light rounded">
                                <label class="form-label fw-semibold small">Ad Soyad</label>
                                <p class="mb-0 small">{{ $user->first_name }} {{ $user->last_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 bg-light rounded">
                                <label class="form-label fw-semibold small">E-posta</label>
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 small">{{ $user->email }}</p>
                                    @if($user->email_verified_at)
                                        <i class="bi bi-check-circle text-success ms-2" title="Email doğrulanmış"></i>
                                    @else
                                        <i class="bi bi-exclamation-circle text-warning ms-2" title="Email doğrulanmamış"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 bg-light rounded">
                                <label class="form-label fw-semibold small">Telefon</label>
                                <p class="mb-0 small">{{ $user->phone ?? 'Belirtilmemiş' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 bg-light rounded">
                                <label class="form-label fw-semibold small">TC Kimlik No</label>
                                <p class="mb-0 small">{{ $user->national_id ?? 'Belirtilmemiş' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 bg-light rounded">
                                <label class="form-label fw-semibold small">Cinsiyet</label>
                                <p class="mb-0 small">
                                    @if($user->gender)
                                        {{ $user->gender == 'male' ? 'Erkek' : ($user->gender == 'female' ? 'Kadın' : 'Diğer') }}
                                    @else
                                        Belirtilmemiş
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 bg-light rounded">
                                <label class="form-label fw-semibold small">Doğum Tarihi</label>
                                <p class="mb-0 small">
                                    {{ $user->birth_date ? $user->birth_date->format('d.m.Y') : 'Belirtilmemiş' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-2 bg-light rounded">
                                <label class="form-label fw-semibold small">Adres</label>
                                <p class="mb-0 small">{{ $user->address ?? 'Belirtilmemiş' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acil Durum Bilgileri -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-telephone me-2"></i>Acil Durum Bilgileri
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-2 bg-light rounded">
                                <label class="form-label fw-semibold small">Acil Durum Kişi Adı</label>
                                <p class="mb-0 small">{{ $user->emergency_contact_name ?? 'Belirtilmemiş' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 bg-light rounded">
                                <label class="form-label fw-semibold small">Acil Durum Telefon</label>
                                <p class="mb-0 small">{{ $user->emergency_contact_phone ?? 'Belirtilmemiş' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daire Bilgileri -->
            @if($user->flats->count() > 0)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-house me-2"></i>Daire Bilgileri
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th class="small fw-bold">Site</th>
                                    <th class="small fw-bold">Blok</th>
                                    <th class="small fw-bold">Apartman</th>
                                    <th class="small fw-bold">Daire</th>
                                    <th class="small fw-bold">Tip</th>
                                    <th class="small fw-bold">Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->flats as $flatResident)
                                    <tr>
                                        <td class="small">{{ $flatResident->flat->apartment->block->site->name ?? '-' }}</td>
                                        <td class="small">{{ $flatResident->flat->apartment->block->name ?? '-' }}</td>
                                        <td class="small">{{ $flatResident->flat->apartment->name ?? '-' }}</td>
                                        <td class="small">{{ $flatResident->flat->flat_number ?? '-' }}</td>
                                        <td class="small">{{ $flatResident->flat->flat_type ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $flatResident->status == 'active' ? 'success' : 'secondary' }} small">
                                                {{ $flatResident->status == 'active' ? 'Aktif' : 'Pasif' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Yan Panel -->
        <div class="col-lg-4">
            <!-- Profil Kartı -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center p-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 80px; height: 80px;">
                        <span class="text-primary fw-bold" style="font-size: 2rem;">
                            {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                        </span>
                    </div>
                    <h5 class="mb-1 fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h5>
                    <p class="text-muted mb-3 small">{{ $user->email }}</p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @if($user->is_active)
                            <span class="badge bg-success small">Aktif</span>
                        @else
                            <span class="badge bg-secondary small">Pasif</span>
                        @endif
                        
                        @if($user->email_verified_at)
                            <span class="badge bg-info small">Doğrulanmış</span>
                        @else
                            <span class="badge bg-warning small">Doğrulanmamış</span>
                        @endif
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Düzenle
                        </a>
                        @if($user->id !== auth()->id())
                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                    onclick="deleteUser({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')">
                                <i class="bi bi-trash me-1"></i>Sil
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sistem Bilgileri -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-gear me-2"></i>Sistem Bilgileri
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded text-center">
                                <div class="text-muted small">Kullanıcı ID</div>
                                <div class="fw-bold small">#{{ $user->id }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded text-center">
                                <div class="text-muted small">Kayıt Tarihi</div>
                                <div class="fw-bold small">{{ $user->created_at->format('d.m.Y') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded text-center">
                                <div class="text-muted small">Son Güncelleme</div>
                                <div class="fw-bold small">{{ $user->updated_at->format('d.m.Y') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded text-center">
                                <div class="text-muted small">Son Giriş</div>
                                <div class="fw-bold small">
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        Hiç giriş yapmamış
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roller ve Yetkiler -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-shield-check me-2"></i>Roller ve Yetkiler
                    </h6>
                </div>
                <div class="card-body p-3">
                    <h6 class="mb-3 small fw-bold">Atanan Roller</h6>
                    @if($user->roles->count() > 0)
                        @foreach($user->roles as $role)
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-secondary me-2 small">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                                <small class="text-muted">({{ $role->permissions->count() }} yetki)</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small">Rol atanmamış</p>
                    @endif

                    @if($user->permissions->count() > 0)
                        <hr>
                        <h6 class="mb-3 small fw-bold">Doğrudan Yetkiler</h6>
                        @foreach($user->permissions as $permission)
                            <span class="badge bg-info me-1 mb-1 small">{{ $permission->name }}</span>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Bildirim Tercihleri -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 p-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-bell me-2"></i>Bildirim Tercihleri
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small">E-posta Bildirimleri</span>
                        @if($user->notification_email)
                            <i class="bi bi-check-circle text-success"></i>
                        @else
                            <i class="bi bi-x-circle text-danger"></i>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="small">SMS Bildirimleri</span>
                        @if($user->notification_sms)
                            <i class="bi bi-check-circle text-success"></i>
                        @else
                            <i class="bi bi-x-circle text-danger"></i>
                        @endif
                    </div>
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

/* Badge improvements */
.badge {
    font-size: 0.75rem;
}

/* Avatar improvements */
.bg-opacity-10 {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}

/* Table improvements */
.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Responsive improvements */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem !important;
    }
}
</style>
@endpush
