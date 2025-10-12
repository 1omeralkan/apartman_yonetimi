@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Yeni Kullanıcı Ekle</h1>
                    <p class="text-muted mb-0">Sisteme yeni bir kullanıcı ekleyin</p>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Geri Dön
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                                @csrf
                                
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
                                               id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Soyad <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                               id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Telefon</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone') }}" 
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
                                               id="national_id" name="national_id" value="{{ old('national_id') }}" 
                                               maxlength="11" placeholder="12345678901">
                                        @error('national_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Cinsiyet</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                            <option value="">Seçiniz</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Erkek</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Kadın</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Diğer</option>
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
                                               id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Şifre <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Şifre Tekrar <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="address" class="form-label">Adres</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="3" 
                                                  placeholder="Tam adres bilgisi">{{ old('address') }}</textarea>
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
                                               value="{{ old('emergency_contact_name') }}">
                                        @error('emergency_contact_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="emergency_contact_phone" class="form-label">Acil Durum Telefon</label>
                                        <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                               id="emergency_contact_phone" name="emergency_contact_phone" 
                                               value="{{ old('emergency_contact_phone') }}" 
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
                                                        {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
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
                                                   value="1" {{ old('is_active', true) ? 'checked' : '' }}>
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
                                                   value="1" {{ old('notification_email', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notification_email">
                                                E-posta Bildirimleri
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="notification_sms" name="notification_sms" 
                                                   value="1" {{ old('notification_sms') ? 'checked' : '' }}>
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
                                        <i class="bi bi-check-circle me-2"></i>Kullanıcı Oluştur
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Yardım Paneli -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>Yardım
                            </h6>
                        </div>
                        <div class="card-body">
                            <h6>Zorunlu Alanlar</h6>
                            <ul class="list-unstyled small">
                                <li><span class="text-danger">*</span> Ad ve soyad</li>
                                <li><span class="text-danger">*</span> E-posta adresi</li>
                                <li><span class="text-danger">*</span> Şifre</li>
                            </ul>

                            <h6 class="mt-3">Önemli Notlar</h6>
                            <ul class="list-unstyled small text-muted">
                                <li>• E-posta adresi benzersiz olmalıdır</li>
                                <li>• TC Kimlik No 11 haneli olmalıdır</li>
                                <li>• Şifre en az 8 karakter olmalıdır</li>
                                <li>• Telefon numarası benzersiz olmalıdır</li>
                                <li>• Kullanıcı oluşturulduktan sonra e-posta doğrulaması gerekebilir</li>
                            </ul>
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
</script>
@endpush
