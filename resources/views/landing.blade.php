<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Apartman Yönetimi') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{ --brand:#2563eb; --brand-600:#1d4ed8; }
        body{ min-height:100vh; display:flex; flex-direction:column; background: radial-gradient(1200px 800px at 10% 10%, rgba(37,99,235,.15), transparent 60%), radial-gradient(1000px 700px at 90% 20%, rgba(16,185,129,.12), transparent 60%), linear-gradient(180deg,#0b1020,#0c1326 35%,#0f172a 100%); color:#e2e8f0; }
        .hero{ flex:1; display:flex; align-items:center; }
        .glass{ background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08); box-shadow:0 20px 60px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.06); backdrop-filter: blur(10px); border-radius:20px; }
        .btn-primary{ background:var(--brand); border-color:var(--brand); box-shadow:0 10px 20px rgba(37,99,235,.25); }
        .btn-primary:hover{ background:var(--brand-600); border-color:var(--brand-600); }
        .feature{ background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.08); border-radius:14px; height:100%; }
        a{ color:#a5b4fc; }
        a:hover{ color:#e0e7ff; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark" style="background:transparent">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="#"><i class="bi bi-buildings"></i> {{ config('app.name','Apartman Yönetimi') }}</a>
        <div class="d-flex gap-2">
            <a href="{{ route('login') }}" class="btn btn-outline-light">Giriş Yap</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Kayıt Ol</a>
        </div>
    </div>
    </nav>

<section class="hero">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="p-4 glass">
                    <h1 class="display-6 fw-bold mb-3">Apartman Yönetimi kolaylaştı</h1>
                    <p class="lead text-secondary">Aidatlar, duyurular, daire planları ve daha fazlasını tek panelden yönetin. Hızlı, güvenli ve modern arayüz ile zamandan kazanın.</p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Giriş Yap</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Hemen Başla</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 feature text-center">
                            <i class="bi bi-diagram-3 display-6"></i>
                            <div class="mt-2">Yerleşim Planı</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 feature text-center">
                            <i class="bi bi-cash-coin display-6"></i>
                            <div class="mt-2">Finans Takibi</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 feature text-center">
                            <i class="bi bi-megaphone display-6"></i>
                            <div class="mt-2">Duyurular</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 feature text-center">
                            <i class="bi bi-people display-6"></i>
                            <div class="mt-2">Sakin Yönetimi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="py-4 text-center text-secondary">
    © {{ date('Y') }} {{ config('app.name','Apartman Yönetimi') }}
    · <a href="{{ route('login') }}">Giriş</a>
    · <a href="{{ route('register') }}">Kayıt</a>
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


