@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Sistem Ayarları</h1>
                    <p class="text-muted mb-0">Uygulama konfigürasyonu ve sistem parametreleri</p>
                </div>
                <a href="{{ route('system.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Geri Dön
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('system.update-settings') }}">
                                @csrf
                                
                                <!-- Uygulama Ayarları -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="card-title mb-3">
                                            <i class="bi bi-gear me-2"></i>Uygulama Ayarları
                                        </h5>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="app_name" class="form-label">Uygulama Adı <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('app_name') is-invalid @enderror" 
                                               id="app_name" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" required>
                                        @error('app_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="app_url" class="form-label">Uygulama URL <span class="text-danger">*</span></label>
                                        <input type="url" class="form-control @error('app_url') is-invalid @enderror" 
                                               id="app_url" name="app_url" value="{{ old('app_url', $settings['app_url']) }}" required>
                                        @error('app_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="app_env" class="form-label">Ortam</label>
                                        <input type="text" class="form-control" id="app_env" value="{{ $settings['app_env'] }}" readonly>
                                        <div class="form-text">Bu değer .env dosyasından okunur</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="app_debug" class="form-label">Debug Modu</label>
                                        <input type="text" class="form-control" id="app_debug" value="{{ $settings['app_debug'] ? 'Açık' : 'Kapalı' }}" readonly>
                                        <div class="form-text">Debug modu .env dosyasında APP_DEBUG ile kontrol edilir</div>
                                    </div>
                                </div>

                                <hr>

                                <!-- Mail Ayarları -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="card-title mb-3">
                                            <i class="bi bi-envelope me-2"></i>Mail Ayarları
                                        </h5>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="mail_mailer" class="form-label">Mail Driver</label>
                                        <input type="text" class="form-control" id="mail_mailer" value="{{ $settings['mail_mailer'] }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mail_host" class="form-label">SMTP Host <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('mail_host') is-invalid @enderror" 
                                               id="mail_host" name="mail_host" value="{{ old('mail_host', $settings['mail_host']) }}" required>
                                        @error('mail_host')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="mail_port" class="form-label">SMTP Port <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('mail_port') is-invalid @enderror" 
                                               id="mail_port" name="mail_port" value="{{ old('mail_port', $settings['mail_port']) }}" required min="1" max="65535">
                                        @error('mail_port')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mail_username" class="form-label">SMTP Kullanıcı Adı <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('mail_username') is-invalid @enderror" 
                                               id="mail_username" name="mail_username" value="{{ old('mail_username', $settings['mail_username']) }}" required>
                                        @error('mail_username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="mail_password" class="form-label">SMTP Şifre</label>
                                        <input type="password" class="form-control @error('mail_password') is-invalid @enderror" 
                                               id="mail_password" name="mail_password" placeholder="Değiştirmek için yeni şifre girin">
                                        @error('mail_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Boş bırakırsanız mevcut şifre korunur</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mail_encryption" class="form-label">Şifreleme</label>
                                        <select class="form-select @error('mail_encryption') is-invalid @enderror" id="mail_encryption" name="mail_encryption">
                                            <option value="">Şifreleme Yok</option>
                                            <option value="tls" {{ old('mail_encryption', $settings['mail_encryption']) == 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ old('mail_encryption', $settings['mail_encryption']) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        </select>
                                        @error('mail_encryption')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr>

                                <!-- Sistem Ayarları -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="card-title mb-3">
                                            <i class="bi bi-sliders me-2"></i>Sistem Konfigürasyonu
                                        </h5>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="cache_driver" class="form-label">Cache Driver</label>
                                        <input type="text" class="form-control" id="cache_driver" value="{{ $settings['cache_driver'] }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="session_driver" class="form-label">Session Driver</label>
                                        <input type="text" class="form-control" id="session_driver" value="{{ $settings['session_driver'] }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="queue_connection" class="form-label">Queue Connection</label>
                                        <input type="text" class="form-control" id="queue_connection" value="{{ $settings['queue_connection'] }}" readonly>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('system.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-2"></i>İptal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Ayarları Kaydet
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
                            <h6>Önemli Notlar</h6>
                            <ul class="list-unstyled small">
                                <li>• Ayarlar değiştirildikten sonra cache otomatik temizlenir</li>
                                <li>• Mail ayarları değiştirildikten sonra test edilmelidir</li>
                                <li>• .env dosyası otomatik güncellenir</li>
                                <li>• Şifre alanı boş bırakılırsa mevcut şifre korunur</li>
                            </ul>

                            <h6 class="mt-3">Desteklenen Mail Servisleri</h6>
                            <ul class="list-unstyled small text-muted">
                                <li>• Gmail (smtp.gmail.com:587)</li>
                                <li>• Outlook (smtp-mail.outlook.com:587)</li>
                                <li>• Yandex (smtp.yandex.com:587)</li>
                                <li>• Özel SMTP sunucuları</li>
                            </ul>

                            <div class="alert alert-warning mt-3">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Uyarı:</strong> Ayarlar değiştirildikten sonra uygulama yeniden başlatılmalıdır.
                            </div>
                        </div>
                    </div>

                    <!-- Hızlı Test -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lightning me-2"></i>Hızlı Test
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('system.health') }}" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-heart-pulse me-2"></i>Sistem Sağlık Kontrolü
                                </a>
                                <a href="{{ route('system.cache') }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-speedometer2 me-2"></i>Cache Yönetimi
                                </a>
                                <a href="{{ route('system.logs') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-file-text me-2"></i>Log Görüntüleme
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
