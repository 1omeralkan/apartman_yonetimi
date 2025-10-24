@extends('layouts.panel')

@section('content')
<div class="container-fluid px-3">
    <!-- Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1 text-dark fw-bold">
                        <i class="bi bi-person-circle me-2 text-primary"></i>
                        Profil Bilgileri
                    </h2>
                    <p class="text-muted mb-0 small">Hesap bilgilerinizi görüntüleyin ve düzenleyin</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-pencil-square me-1"></i>
                        Düzenle
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="bi bi-shield-lock me-1"></i>
                        Şifre Değiştir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Profil Kartı -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="position-relative d-inline-block mb-3">
                        @if($user->profile_photo_path)
                            <img src="{{ Storage::url($user->profile_photo_path) }}" 
                                 alt="Profil Fotoğrafı" 
                                 class="rounded-circle border border-3 border-white shadow-sm"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" 
                                 style="width: 80px; height: 80px; font-size: 2rem; font-weight: 600;">
                                {{ strtoupper(Str::substr($user->first_name ?? $user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="position-absolute bottom-0 end-0">
                            <span class="badge bg-success rounded-circle p-1" title="Aktif Kullanıcı" style="font-size: 0.7rem;">
                                <i class="bi bi-check-lg"></i>
                            </span>
                        </div>
                        <div class="position-absolute top-0 end-0">
                            <button type="button" class="btn btn-sm btn-primary rounded-circle p-1" 
                                    data-bs-toggle="modal" data-bs-target="#photoUploadModal" 
                                    title="Fotoğraf Değiştir" style="width: 24px; height: 24px;">
                                <i class="bi bi-camera" style="font-size: 0.7rem;"></i>
                            </button>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $user->first_name ?? $user->name }} {{ $user->last_name }}</h5>
                    <p class="text-muted mb-2 small">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="text-muted mb-2 small"><i class="bi bi-telephone me-1"></i>{{ $user->phone }}</p>
                    @endif
                    
                    <div class="d-flex justify-content-center gap-1 mb-3 flex-wrap">
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary small">{{ ucfirst($role->name) }}</span>
                        @endforeach
                    </div>
                    
                    <div class="text-start">
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span class="text-muted small">Kayıt Tarihi</span>
                            <span class="fw-medium small">{{ $user->created_at->format('d.m.Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span class="text-muted small">Son Giriş</span>
                            <span class="fw-medium small">{{ $user->updated_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1">
                            <span class="text-muted small">Durum</span>
                            <span class="badge bg-success small">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detay Bilgiler -->
        <div class="col-lg-8">
            <div class="row g-3">
                <!-- Kişisel Bilgiler -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-person me-2 text-primary"></i>
                                Kişisel Bilgiler
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label text-muted small">Ad Soyad</label>
                                    <div class="fw-medium small">{{ $user->first_name ?? $user->name }} {{ $user->last_name }}</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small">E-posta</label>
                                    <div class="fw-medium small">{{ $user->email }}</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small">Telefon</label>
                                    <div class="fw-medium small">{{ $user->phone ?? 'Belirtilmemiş' }}</div>
                                </div>
                                @if($user->national_id)
                                <div class="col-12">
                                    <label class="form-label text-muted small">TC Kimlik No</label>
                                    <div class="fw-medium small">{{ $user->national_id }}</div>
                                </div>
                                @endif
                                @if($user->gender)
                                <div class="col-12">
                                    <label class="form-label text-muted small">Cinsiyet</label>
                                    <div class="fw-medium small">{{ $user->gender == 'male' ? 'Erkek' : 'Kadın' }}</div>
                                </div>
                                @endif
                                @if($user->birth_date)
                                <div class="col-12">
                                    <label class="form-label text-muted small">Doğum Tarihi</label>
                                    <div class="fw-medium small">{{ $user->birth_date->format('d.m.Y') }}</div>
                                </div>
                                @endif
                                @if($user->address)
                                <div class="col-12">
                                    <label class="form-label text-muted small">Adres</label>
                                    <div class="fw-medium small">{{ $user->address }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hesap Bilgileri -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-shield-check me-2 text-success"></i>
                                Hesap Bilgileri
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label text-muted small">Kullanıcı ID</label>
                                    <div class="fw-medium small">#{{ $user->id }}</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small">Roller</label>
                                    <div>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary me-1 small">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small">E-posta Doğrulama</label>
                                    <div>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success small">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Doğrulanmış
                                            </span>
                                        @else
                                            <span class="badge bg-warning small">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                Doğrulanmamış
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acil Durum Bilgileri -->
                @if($user->emergency_contact_name || $user->emergency_contact_phone)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-person-heart me-2 text-warning"></i>
                                Acil Durum İletişim
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                @if($user->emergency_contact_name)
                                <div class="col-12">
                                    <label class="form-label text-muted small">Acil Durum Kişi</label>
                                    <div class="fw-medium small">{{ $user->emergency_contact_name }}</div>
                                </div>
                                @endif
                                @if($user->emergency_contact_phone)
                                <div class="col-12">
                                    <label class="form-label text-muted small">Acil Durum Telefon</label>
                                    <div class="fw-medium small">{{ $user->emergency_contact_phone }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Bildirim Tercihleri -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-bell me-2 text-info"></i>
                                Bildirim Tercihleri
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-envelope {{ $user->notification_email ? 'text-success' : 'text-muted' }}"></i>
                                        <span class="fw-medium small">E-posta Bildirimleri</span>
                                        <span class="badge {{ $user->notification_email ? 'bg-success' : 'bg-secondary' }} small">
                                            {{ $user->notification_email ? 'Açık' : 'Kapalı' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-chat-dots {{ $user->notification_sms ? 'text-success' : 'text-muted' }}"></i>
                                        <span class="fw-medium small">SMS Bildirimleri</span>
                                        <span class="badge {{ $user->notification_sms ? 'bg-success' : 'bg-secondary' }} small">
                                            {{ $user->notification_sms ? 'Açık' : 'Kapalı' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- İstatistikler -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-graph-up me-2 text-info"></i>
                                Hesap İstatistikleri
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-md-3 col-6">
                                    <div class="text-center p-2 bg-light rounded-2">
                                        <i class="bi bi-calendar-check text-success fs-5 mb-1"></i>
                                        <div class="fw-bold text-dark small">{{ $user->created_at->diffInDays(now()) }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Gün Üye</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center p-2 bg-light rounded-2">
                                        <i class="bi bi-clock-history text-primary fs-5 mb-1"></i>
                                        <div class="fw-bold text-dark small">{{ $user->updated_at->format('H:i') }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Son Aktivite</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center p-2 bg-light rounded-2">
                                        <i class="bi bi-shield-check text-success fs-5 mb-1"></i>
                                        <div class="fw-bold text-dark small">Aktif</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Hesap Durumu</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center p-2 bg-light rounded-2">
                                        <i class="bi bi-person-badge text-info fs-5 mb-1"></i>
                                        <div class="fw-bold text-dark small">{{ $user->roles->count() }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Rol Sayısı</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profil Düzenleme Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editProfileModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>
                    Profil Bilgilerini Düzenle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('account.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-3" style="max-height: 75vh; overflow-y: auto;">
                    
                    <!-- Kişisel Bilgiler -->
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-person me-2 text-primary"></i>
                                Kişisel Bilgiler
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">Ad <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" 
                                           value="{{ old('first_name', $user->first_name ?? $user->name) }}" 
                                           required autocomplete="given-name" placeholder="Adınızı girin">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Soyad <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" 
                                           value="{{ old('last_name', $user->last_name) }}" 
                                           required autocomplete="family-name" placeholder="Soyadınızı girin">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="national_id" class="form-label">TC Kimlik No</label>
                                    <input type="text" class="form-control @error('national_id') is-invalid @enderror" 
                                           id="national_id" name="national_id" 
                                           value="{{ old('national_id', $user->national_id) }}" 
                                           autocomplete="off" placeholder="TC Kimlik numaranız" maxlength="11">
                                    @error('national_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Cinsiyet</label>
                                    <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                                        <option value="">Seçiniz</option>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Erkek</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Kadın</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="birth_date" class="form-label">Doğum Tarihi</label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                           id="birth_date" name="birth_date" 
                                           value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- İletişim Bilgileri -->
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-telephone me-2 text-success"></i>
                                İletişim Bilgileri
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required autocomplete="username" placeholder="ornek@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Telefon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" 
                                           value="{{ old('phone', $user->phone) }}" 
                                           autocomplete="tel" placeholder="0555 123 45 67">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="address" class="form-label">Adres</label>
                                    <textarea id="address" name="address" rows="3" 
                                              class="form-control @error('address') is-invalid @enderror" 
                                              placeholder="Tam adresinizi girin">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acil Durum İletişim -->
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-person-heart me-2 text-warning"></i>
                                Acil Durum İletişim
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="emergency_contact_name" class="form-label">Acil Durum Kişi</label>
                                    <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                           id="emergency_contact_name" name="emergency_contact_name" 
                                           value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}" 
                                           placeholder="Acil durum kişisinin adı">
                                    @error('emergency_contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="emergency_contact_phone" class="form-label">Acil Durum Telefon</label>
                                    <input type="text" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                           id="emergency_contact_phone" name="emergency_contact_phone" 
                                           value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}" 
                                           placeholder="0555 123 45 67">
                                    @error('emergency_contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bildirim Tercihleri -->
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-bell me-2 text-info"></i>
                                Bildirim Tercihleri
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="notification_email" 
                                               name="notification_email" value="1" 
                                               {{ old('notification_email', $user->notification_email) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notification_email">
                                            E-posta Bildirimleri
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="notification_sms" 
                                               name="notification_sms" value="1" 
                                               {{ old('notification_sms', $user->notification_sms) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notification_sms">
                                            SMS Bildirimleri
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>
                        İptal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Fotoğraf Yükleme Modal -->
<div class="modal fade" id="photoUploadModal" tabindex="-1" aria-labelledby="photoUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="photoUploadModalLabel">
                    <i class="bi bi-camera me-2"></i>
                    Profil Fotoğrafı
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('account.photo.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        @if($user->profile_photo_path)
                            <img id="currentPhoto" src="{{ Storage::url($user->profile_photo_path) }}" 
                                 alt="Mevcut Fotoğraf" 
                                 class="rounded-circle border border-3 border-light shadow-sm mb-3"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div id="currentPhoto" class="rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white mx-auto mb-3" 
                                 style="width: 120px; height: 120px; font-size: 3rem; font-weight: 600;">
                                {{ strtoupper(Str::substr($user->first_name ?? $user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div id="previewPhoto" class="d-none">
                            <img id="previewImg" src="" alt="Önizleme" 
                                 class="rounded-circle border border-3 border-primary shadow-sm mb-3"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label">Fotoğraf Seç</label>
                        <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" 
                               id="profile_photo" name="profile_photo" accept="image/*" required>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Maksimum 5MB, JPG/PNG/GIF formatında
                        </div>
                        @error('profile_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @if($user->profile_photo_path)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Yeni fotoğraf yüklediğinizde mevcut fotoğraf silinecektir.
                    </div>
                    @endif
                </div>
                <div class="modal-footer bg-light">
                    @if($user->profile_photo_path)
                    <button type="button" class="btn btn-outline-danger" onclick="deletePhoto()">
                        <i class="bi bi-trash me-1"></i>
                        Fotoğrafı Sil
                    </button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>
                        İptal
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-upload me-1"></i>
                        Yükle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Şifre Değiştirme Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="bi bi-shield-lock me-2"></i>
                    Şifre Değiştir
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('account.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Güvenliğiniz için mevcut şifrenizi girin.
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="current_password" class="form-label">Mevcut Şifre</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="col-12">
                            <label for="password" class="form-label">Yeni Şifre</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-12">
                            <label for="password_confirmation" class="form-label">Yeni Şifre (Tekrar)</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>
                        İptal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-shield-check me-1"></i>
                        Şifreyi Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validasyonu
    document.addEventListener('DOMContentLoaded', function() {
        // Profil düzenleme formu
        const editForm = document.querySelector('#editProfileModal form');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                const firstName = document.getElementById('first_name').value.trim();
                const email = document.getElementById('email').value.trim();
                
                if (!firstName || !email) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: 'Lütfen tüm gerekli alanları doldurun.',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });
        }

        // Şifre değiştirme formu
        const passwordForm = document.querySelector('#changePasswordModal form');
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;
                
                if (password !== passwordConfirmation) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: 'Şifreler eşleşmiyor.',
                        confirmButtonColor: '#2563eb'
                    });
                }
                
                if (password.length < 8) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: 'Şifre en az 8 karakter olmalıdır.',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });
        }

        // Fotoğraf önizleme
        const photoInput = document.getElementById('profile_photo');
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Dosya boyutu kontrolü (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Dosya Çok Büyük!',
                            text: 'Fotoğraf maksimum 5MB olmalıdır.',
                            confirmButtonColor: '#2563eb'
                        });
                        e.target.value = '';
                        return;
                    }

                    // Dosya tipi kontrolü
                    if (!file.type.startsWith('image/')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Geçersiz Dosya!',
                            text: 'Lütfen sadece resim dosyası seçin.',
                            confirmButtonColor: '#2563eb'
                        });
                        e.target.value = '';
                        return;
                    }

                    // Önizleme göster
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('currentPhoto').style.display = 'none';
                        document.getElementById('previewPhoto').classList.remove('d-none');
                        document.getElementById('previewImg').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    // Fotoğraf silme fonksiyonu
    function deletePhoto() {
        Swal.fire({
            title: 'Emin misiniz?',
            text: 'Profil fotoğrafınız silinecektir.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Evet, Sil',
            cancelButtonText: 'İptal',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                // CSRF token al
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch('{{ route("account.photo.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı!',
                            text: 'Profil fotoğrafınız silindi.',
                            confirmButtonColor: '#2563eb'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: data.message || 'Fotoğraf silinirken bir hata oluştu.',
                            confirmButtonColor: '#2563eb'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: 'Bir hata oluştu.',
                        confirmButtonColor: '#2563eb'
                    });
                });
            }
        });
    }
</script>
@endpush
