<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Apartman Yönetimi') }} - Şifremi Unuttum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{ --brand:#2563eb; --brand-600:#1d4ed8; }
        body{ min-height:100vh; background: radial-gradient(1200px 800px at 10% 10%, rgba(37,99,235,.15), transparent 60%), radial-gradient(1000px 700px at 90% 20%, rgba(16,185,129,.12), transparent 60%), linear-gradient(180deg,#0b1020,#0c1326 35%,#0f172a 100%); color:#e2e8f0; display:flex; align-items:center; justify-content:center; padding:32px 16px; }
        .card-auth{ width:100%; max-width: 520px; border:0; border-radius:18px; background:rgba(255,255,255,.04); box-shadow: 0 20px 60px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.06); backdrop-filter: blur(10px); }
        .card-auth .card-body{ padding:28px; }
        .brand{ display:flex; align-items:center; gap:10px; color:#f8fafc; font-weight:600; letter-spacing:.3px; }
        .brand i{ color:var(--brand); }
        .btn-primary{ background:var(--brand); border-color:var(--brand); box-shadow:0 10px 20px rgba(37,99,235,.25); }
        .btn-primary:hover{ background:var(--brand-600); border-color:var(--brand-600); }
        .form-label{ color:#cbd5e1; font-weight:500; }
        .form-control{ border-radius:12px; background: rgba(255,255,255,.06); border-color:rgba(255,255,255,.14); color:#f1f5f9 !important; caret-color:#eaf2ff; }
        .form-control::placeholder{ color:#94a3b8; }
        .form-control:focus{ border-color: rgba(37,99,235,.7); box-shadow:0 0 0 .25rem rgba(37,99,235,.18); background: rgba(255,255,255,.12); color:#fff; }
        input,
        select,
        textarea{ color:#eaf2ff !important; }
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus{ -webkit-text-fill-color:#eaf2ff !important; caret-color:#eaf2ff; -webkit-box-shadow:0 0 0 1000px rgba(255,255,255,.06) inset !important; box-shadow:0 0 0 1000px rgba(255,255,255,.06) inset !important; transition: background-color 9999s ease-in-out 0s; }
        .text-muted{ color:#cbd5e1 !important; }
        a{ color:#a5b4fc; }
        a:hover{ color:#e0e7ff; }
        .auth-footer{ color:#94a3b8; font-size:.85rem; text-align:center; margin-top:12px; }
    </style>
</head>
<body>
<div class="card card-auth">
    <div class="card-body">
        <div class="brand mb-3"><i class="bi bi-envelope-at-fill"></i> Şifremi Unuttum</div>
        <p class="text-muted">Email adresini yaz; sana şifre sıfırlama bağlantısı gönderelim.</p>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
            @csrf
            <div>
                <label class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
            </div>
            <button class="btn btn-primary w-100">Sıfırlama Bağlantısı Gönder</button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}">Giriş sayfasına dön</a>
        </div>
        <div class="auth-footer">© {{ date('Y') }} {{ config('app.name') }}</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
