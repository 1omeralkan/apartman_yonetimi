@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Kullanıcı Düzenle</h1>
                    <p class="text-muted mb-0">{{ $user->first_name }} {{ $user->last_name }} kullanıcısını düzenleyin</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info">
                        <i class="bi bi-eye me-2"></i>Görüntüle
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Geri Dön
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <!-- Kişisel Bilgiler -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="card-title mb-3">
                                            <i class="bi bi-person me-2"></i>Kişisel Bilgiler
                                        </h5>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">Ad <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                               id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Soyad <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                               id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Telefon</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                               placeholder="0555 123 45 67">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="national_id" class="form-label">TC Kimlik No</label>
                                        <input type="text" class="form-control @error('national_id') is-invalid @enderror" 
                                               id="national_id" name="national_id" value="{{ old('national_id', $user->national_id) }}" 
                                               maxlength="11" placeholder="12345678901">
                                        @error('national_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Cinsiyet</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                            <option value="">Seçiniz</option>
                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Erkek</option>
                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Kadın</option>
                                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Diğer</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="birth_date" class="form-label">Doğum Tarihi</label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                               id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Yeni Şifre</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password">
                                        <div class="form-text">Boş bırakırsanız mevcut şifre korunur</div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Yeni Şifre Tekrar</label>
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="address" class="form-label">Adres</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="3" 
                                                  placeholder="Tam adres bilgisi">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Acil Durum Bilgileri -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="card-title mb-3">
                                            <i class="bi bi-telephone me-2"></i>Acil Durum Bilgileri
                                        </h5>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="emergency_contact_name" class="form-label">Acil Durum Kişi Adı</label>
                                        <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                               id="emergency_contact_name" name="emergency_contact_name" 
                                               value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}">
                                        @error('emergency_contact_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="emergency_contact_phone" class="form-label">Acil Durum Telefon</label>
                                        <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                               id="emergency_contact_phone" name="emergency_contact_phone" 
                                               value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}" 
                                               placeholder="0555 123 45 67">
                                        @error('emergency_contact_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Sistem Ayarları -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="card-title mb-3">
                                            <i class="bi bi-gear me-2"></i>Sistem Ayarları
                                        </h5>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="roles" class="form-label">Roller</label>
                                        <select class="form-select @error('roles') is-invalid @enderror" 
                                                id="roles" name="roles[]" multiple>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" 
                                                        {{ in_array($role->id, old('roles', $userRoles)) ? 'selected' : '' }}>
                                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Ctrl tuşu ile birden fazla rol seçebilirsiniz</div>
                                        @error('roles')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Kullanıcı Aktif
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="notification_email" name="notification_email" 
                                                   value="1" {{ old('notification_email', $user->notification_email) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notification_email">
                                                E-posta Bildirimleri
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="notification_sms" name="notification_sms" 
                                                   value="1" {{ old('notification_sms', $user->notification_sms) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notification_sms">
                                                SMS Bildirimleri
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-2"></i>İptal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Değişiklikleri Kaydet
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Kullanıcı Bilgileri Paneli -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>Kullanıcı Bilgileri
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                                        {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                                    </div>
                                </div>
                                <h6 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                <p class="text-muted mb-0">{{ $user->email }}</p>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-badge text-muted me-2"></i>
                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar text-muted me-2"></i>
                                        <small class="text-muted">{{ $user->created_at->format('d.m.Y') }}</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock text-muted me-2"></i>
                                        <small class="text-muted">
                                            @if($user->last_login_at)
                                                {{ $user->last_login_at->diffForHumans() }}
                                            @else
                                                Hiç giriş yapmamış
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-shield-check text-muted me-2"></i>
                                        <small class="text-muted">
                                            @if($user->email_verified_at)
                                                Doğrulanmış
                                            @else
                                                Doğrulanmamış
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <h6 class="mb-2">Mevcut Roller</h6>
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    <span class="badge bg-secondary me-1 mb-1">
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted small">Rol atanmamış</span>
                            @endif
                        </div>
                    </div>

                    <!-- Hızlı İşlemler -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lightning me-2"></i>Hızlı İşlemler
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-outline-warning btn-sm" 
                                            onclick="resetPassword({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')">
                                        <i class="bi bi-key me-2"></i>Şifre Sıfırla
                                    </button>
                                    
                                    <button type="button" class="btn btn-outline-{{ $user->is_active ? 'secondary' : 'success' }} btn-sm" 
                                            onclick="toggleStatus({{ $user->id }}, {{ $user->is_active ? 'false' : 'true' }}, '{{ $user->first_name }} {{ $user->last_name }}')">
                                        <i class="bi bi-{{ $user->is_active ? 'pause' : 'play' }} me-2"></i>
                                        {{ $user->is_active ? 'Pasifleştir' : 'Aktifleştir' }}
                                    </button>
                                @else
                                    <small class="text-muted">Kendi hesabınız için bu işlemler kullanılamaz</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Şifre Sıfırlama Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Şifre Sıfırla</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="resetPasswordForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p><strong id="resetUserName"></strong> adlı kullanıcının şifresini sıfırlamak istediğinizden emin misiniz?</p>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Yeni Şifre</label>
                        <input type="password" class="form-control" id="new_password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Yeni Şifre Tekrar</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-warning">Şifre Sıfırla</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Durum Değiştirme Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kullanıcı Durumu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="toggleStatusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p><strong id="toggleUserName"></strong> adlı kullanıcının durumunu değiştirmek istediğinizden emin misiniz?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Değiştir</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// TC Kimlik No sadece rakam girişi
document.getElementById('national_id').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Telefon numarası formatlaması
function formatPhone(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 10) {
        value = value.replace(/(\d{4})(\d{3})(\d{2})(\d{2})/, '$1 $2 $3 $4');
    }
    input.value = value;
}

document.getElementById('phone').addEventListener('input', function(e) {
    formatPhone(this);
});

document.getElementById('emergency_contact_phone').addEventListener('input', function(e) {
    formatPhone(this);
});

// Şifre sıfırlama
function resetPassword(userId, userName) {
    document.getElementById('resetUserName').textContent = userName;
    document.getElementById('resetPasswordForm').action = `/users/${userId}/reset-password`;
    new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
}

// Durum değiştirme
function toggleStatus(userId, newStatus, userName) {
    document.getElementById('toggleUserName').textContent = userName;
    document.getElementById('toggleStatusForm').action = `/users/${userId}/toggle-status`;
    new bootstrap.Modal(document.getElementById('toggleStatusModal')).show();
}
</script>
@endpush
