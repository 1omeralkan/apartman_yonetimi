<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Apartman Yönetimi') }} - Kayıt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{ --brand:#2563eb; --brand-600:#1d4ed8; }
        body{ min-height:100vh; background: radial-gradient(1200px 800px at 10% 10%, rgba(37,99,235,.15), transparent 60%), radial-gradient(1000px 700px at 90% 20%, rgba(16,185,129,.12), transparent 60%), linear-gradient(180deg,#0b1020,#0c1326 35%,#0f172a 100%); color:#e2e8f0; display:flex; align-items:center; justify-content:center; padding:32px 16px; }
        .card-auth{ width:100%; max-width: 800px; border:0; border-radius:18px; background:rgba(255,255,255,.04); box-shadow: 0 20px 60px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.06); backdrop-filter: blur(10px); }
        .card-auth .card-body{ padding:28px; }
        .brand{ display:flex; align-items:center; gap:10px; color:#f8fafc; font-weight:600; letter-spacing:.3px; }
        .brand i{ color:var(--brand); }
        .btn-primary{ background:var(--brand); border-color:var(--brand); box-shadow:0 10px 20px rgba(37,99,235,.25); }
        .btn-primary:hover{ background:var(--brand-600); border-color:var(--brand-600); }
        .form-label{ color:#cbd5e1; font-weight:500; }
        .form-control{ border-radius:12px; background: rgba(255,255,255,.06); border-color:rgba(255,255,255,.14); color:#f1f5f9 !important; caret-color:#eaf2ff; }
        .form-control::placeholder{ color:#94a3b8; }
        .form-control:focus{ border-color: rgba(37,99,235,.7); box-shadow:0 0 0 .25rem rgba(37,99,235,.18); background: rgba(255,255,255,.12); color:#fff; }
        .divider{ display:flex; align-items:center; gap:10px; color:#9aa5b1; font-size:.85rem; }
        .divider::before, .divider::after{ content:""; flex:1; height:1px; background:rgba(255,255,255,.12); }
        a{ color:#a5b4fc; }
        a:hover{ color:#e0e7ff; }
        .auth-footer{ color:#94a3b8; font-size:.85rem; text-align:center; margin-top:12px; }
        input,
        select,
        textarea{ color:#eaf2ff !important; }
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus{ -webkit-text-fill-color:#eaf2ff !important; caret-color:#eaf2ff; -webkit-box-shadow:0 0 0 1000px rgba(255,255,255,.06) inset !important; box-shadow:0 0 0 1000px rgba(255,255,255,.06) inset !important; transition: background-color 9999s ease-in-out 0s; }
        
        /* Bölüm başlıkları */
        .section-title {
            color: #e2e8f0;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        
        /* Form grupları */
        .form-section {
            background: rgba(255,255,255,.02);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255,255,255,.05);
        }
        
        /* Placeholder stilleri */
        .form-control::placeholder {
            color: #94a3b8;
            font-size: 0.9rem;
        }
        
        /* Select stilleri */
        .form-select {
            background: rgba(255,255,255,.06);
            border-color: rgba(255,255,255,.14);
            color: #eaf2ff !important;
        }
        
        .form-select:focus {
            border-color: rgba(37,99,235,.7);
            box-shadow: 0 0 0 .25rem rgba(37,99,235,.18);
            background: rgba(255,255,255,.12);
        }
        
        /* Select option stilleri */
        .form-select option {
            background: #1e293b !important;
            color: #e2e8f0 !important;
            padding: 8px 12px;
        }
        
        .form-select option:hover {
            background: #334155 !important;
            color: #f1f5f9 !important;
        }
        
        .form-select option:checked {
            background: #2563eb !important;
            color: #ffffff !important;
        }
        
        /* Textarea stilleri */
        .form-control[rows] {
            resize: vertical;
            min-height: 80px;
        }
        
        /* Select dropdown daha iyi görünürlük */
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23e2e8f0' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }
        
        /* Select option'lar için daha koyu arka plan */
        .form-select option {
            background: #0f172a !important;
            color: #f8fafc !important;
            font-weight: 500;
        }
        
        /* Select option hover efekti */
        .form-select option:hover,
        .form-select option:focus {
            background: #1e293b !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body>
<div class="card card-auth">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="brand"><i class="bi bi-person-plus-fill"></i> Kayıt Ol</div>
            <a href="{{ url('/') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-house-door"></i> Ana Sayfa</a>
        </div>

        <form method="POST" action="{{ route('register') }}" class="vstack gap-3">
            @csrf
            
            <!-- Kişisel Bilgiler -->
            <div class="form-section">
                <h6 class="section-title">
                    <i class="bi bi-person me-2"></i>Kişisel Bilgiler
                </h6>
            
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label">Ad <span class="text-danger">*</span></label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" 
                           class="form-control @error('first_name') is-invalid @enderror" 
                           required autofocus autocomplete="given-name" placeholder="Adınızı girin">
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Soyad <span class="text-danger">*</span></label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" 
                           class="form-control @error('last_name') is-invalid @enderror" 
                           required autocomplete="family-name" placeholder="Soyadınızı girin">
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label">TC Kimlik No</label>
                    <input id="national_id" type="text" name="national_id" value="{{ old('national_id') }}" 
                           class="form-control @error('national_id') is-invalid @enderror" 
                           autocomplete="off" placeholder="TC Kimlik numaranız" maxlength="11">
                    @error('national_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cinsiyet</label>
                    <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                        <option value="">Seçiniz</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Erkek</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Kadın</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Doğum Tarihi</label>
                <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date') }}" 
                       class="form-control @error('birth_date') is-invalid @enderror">
                @error('birth_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            </div>

            <!-- İletişim Bilgileri -->
            <div class="form-section">
                <h6 class="section-title">
                    <i class="bi bi-telephone me-2"></i>İletişim Bilgileri
                </h6>

            <div>
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" 
                       class="form-control @error('email') is-invalid @enderror" 
                       required autocomplete="username" placeholder="ornek@email.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="form-label">Telefon</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" 
                       class="form-control @error('phone') is-invalid @enderror" 
                       autocomplete="tel" placeholder="0555 123 45 67">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="form-label">Adres</label>
                <textarea id="address" name="address" rows="2" 
                          class="form-control @error('address') is-invalid @enderror" 
                          placeholder="Tam adresinizi girin">{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            </div>

            <!-- Acil Durum İletişim -->
            <div class="form-section">
                <h6 class="section-title">
                    <i class="bi bi-person-heart me-2"></i>Acil Durum İletişim
                </h6>

            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label">Acil Durum Kişi</label>
                    <input id="emergency_contact_name" type="text" name="emergency_contact_name" 
                           value="{{ old('emergency_contact_name') }}" 
                           class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                           placeholder="Acil durum kişisinin adı">
                    @error('emergency_contact_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Acil Durum Telefon</label>
                    <input id="emergency_contact_phone" type="text" name="emergency_contact_phone" 
                           value="{{ old('emergency_contact_phone') }}" 
                           class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                           placeholder="0555 123 45 67">
                    @error('emergency_contact_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            </div>

            <!-- Güvenlik -->
            <div class="form-section">
                <h6 class="section-title">
                    <i class="bi bi-shield-lock me-2"></i>Güvenlik
                </h6>

            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label">Şifre <span class="text-danger">*</span></label>
                    <input id="password" type="password" name="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           required autocomplete="new-password" placeholder="En az 8 karakter">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Şifre (Tekrar) <span class="text-danger">*</span></label>
                    <input id="password_confirmation" type="password" name="password_confirmation" 
                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                           required autocomplete="new-password" placeholder="Şifrenizi tekrar girin">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">
                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'">'.__('Privacy Policy').'</a>',
                        ]) !!}
                    </label>
                </div>
            @endif

            <button class="btn btn-primary w-100">Kayıt Ol</button>
        </form>

        <div class="divider my-3">veya</div>
        <div class="text-center">
            <a href="{{ route('login') }}">Zaten hesabın var mı? Giriş yap</a>
        </div>
        <div class="auth-footer">© {{ date('Y') }} {{ config('app.name') }}</div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
