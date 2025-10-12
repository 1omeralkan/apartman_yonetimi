@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Kullanıcı Detayı</h1>
                    <p class="text-muted mb-0">{{ $user->first_name }} {{ $user->last_name }} kullanıcısının detaylı bilgileri</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Düzenle
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Geri Dön
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Ana Bilgiler -->
                <div class="col-lg-8">
                    <!-- Kişisel Bilgiler -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person me-2"></i>Kişisel Bilgiler
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Ad Soyad</label>
                                        <p class="form-control-plaintext">{{ $user->first_name }} {{ $user->last_name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">E-posta</label>
                                        <div class="d-flex align-items-center">
                                            <p class="form-control-plaintext mb-0">{{ $user->email }}</p>
                                            @if($user->email_verified_at)
                                                <i class="bi bi-check-circle text-success ms-2" title="Email doğrulanmış"></i>
                                            @else
                                                <i class="bi bi-exclamation-circle text-warning ms-2" title="Email doğrulanmamış"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Telefon</label>
                                        <p class="form-control-plaintext">{{ $user->phone ?? 'Belirtilmemiş' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">TC Kimlik No</label>
                                        <p class="form-control-plaintext">{{ $user->national_id ?? 'Belirtilmemiş' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Cinsiyet</label>
                                        <p class="form-control-plaintext">
                                            @if($user->gender)
                                                {{ $user->gender == 'male' ? 'Erkek' : ($user->gender == 'female' ? 'Kadın' : 'Diğer') }}
                                            @else
                                                Belirtilmemiş
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Doğum Tarihi</label>
                                        <p class="form-control-plaintext">
                                            {{ $user->birth_date ? $user->birth_date->format('d.m.Y') : 'Belirtilmemiş' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Adres</label>
                                        <p class="form-control-plaintext">{{ $user->address ?? 'Belirtilmemiş' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acil Durum Bilgileri -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-telephone me-2"></i>Acil Durum Bilgileri
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Acil Durum Kişi Adı</label>
                                        <p class="form-control-plaintext">{{ $user->emergency_contact_name ?? 'Belirtilmemiş' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Acil Durum Telefon</label>
                                        <p class="form-control-plaintext">{{ $user->emergency_contact_phone ?? 'Belirtilmemiş' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daire Bilgileri -->
                    @if($user->flats->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-house me-2"></i>Daire Bilgileri
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Site</th>
                                            <th>Blok</th>
                                            <th>Apartman</th>
                                            <th>Daire</th>
                                            <th>Tip</th>
                                            <th>Durum</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->flats as $flatResident)
                                            <tr>
                                                <td>{{ $flatResident->flat->apartment->block->site->name ?? '-' }}</td>
                                                <td>{{ $flatResident->flat->apartment->block->name ?? '-' }}</td>
                                                <td>{{ $flatResident->flat->apartment->name ?? '-' }}</td>
                                                <td>{{ $flatResident->flat->flat_number ?? '-' }}</td>
                                                <td>{{ $flatResident->flat->flat_type ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $flatResident->status == 'active' ? 'success' : 'secondary' }}">
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
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle" style="width: 100px; height: 100px; font-size: 3rem;">
                                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                                </div>
                            </div>
                            <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                            <p class="text-muted mb-3">{{ $user->email }}</p>
                            
                            <div class="d-flex justify-content-center gap-2 mb-3">
                                @if($user->is_active)
                                    <span class="badge bg-success fs-6">Aktif</span>
                                @else
                                    <span class="badge bg-secondary fs-6">Pasif</span>
                                @endif
                                
                                @if($user->email_verified_at)
                                    <span class="badge bg-info fs-6">Doğrulanmış</span>
                                @else
                                    <span class="badge bg-warning fs-6">Doğrulanmamış</span>
                                @endif
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil me-2"></i>Düzenle
                                </a>
                                @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="deleteUser({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')">
                                        <i class="bi bi-trash me-2"></i>Sil
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sistem Bilgileri -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-gear me-2"></i>Sistem Bilgileri
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="text-muted small">Kullanıcı ID</div>
                                        <div class="fw-semibold">#{{ $user->id }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="text-muted small">Kayıt Tarihi</div>
                                        <div class="fw-semibold">{{ $user->created_at->format('d.m.Y') }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="text-muted small">Son Güncelleme</div>
                                        <div class="fw-semibold">{{ $user->updated_at->format('d.m.Y') }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="text-muted small">Son Giriş</div>
                                        <div class="fw-semibold">
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
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-shield-check me-2"></i>Roller ve Yetkiler
                            </h6>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-3">Atanan Roller</h6>
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-secondary me-2">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                                        <small class="text-muted">({{ $role->permissions->count() }} yetki)</small>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small">Rol atanmamış</p>
                            @endif

                            @if($user->permissions->count() > 0)
                                <hr>
                                <h6 class="mb-3">Doğrudan Yetkiler</h6>
                                @foreach($user->permissions as $permission)
                                    <span class="badge bg-info me-1 mb-1 small">{{ $permission->name }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Bildirim Tercihleri -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-bell me-2"></i>Bildirim Tercihleri
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span>E-posta Bildirimleri</span>
                                @if($user->notification_email)
                                    <i class="bi bi-check-circle text-success"></i>
                                @else
                                    <i class="bi bi-x-circle text-danger"></i>
                                @endif
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span>SMS Bildirimleri</span>
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
