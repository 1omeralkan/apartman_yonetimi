<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Apartman Yönetimi') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            --brand-primary: #2563eb;
            --brand-primary-600:#1d4ed8;
            --brand-primary-50:#eff6ff;
            --ink:#0f172a;
            --muted:#64748b;
            --sidebar-bg:#0b1220;
            --sidebar-hover:#101a30;
            --sidebar-text:#cbd5e1;
            --success:#16a34a; --warning:#f59e0b; --danger:#ef4444; --secondary:#475569;
        }
        html,body{ height:100%; }
        body{ background:linear-gradient(180deg,#f7f9fc 0,#eef3f9 100%); color:var(--ink); }
        .navbar{ backdrop-filter:saturate(140%) blur(6px); border-bottom:1px solid rgba(2,6,23,.06); }
        .sidebar{ width:280px; min-height:100vh; background:var(--sidebar-bg); position:sticky; top:0; }
        .sidebar .brand{ color:#fff; font-weight:600; letter-spacing:.3px; }
        .sidebar a{ color:var(--sidebar-text); text-decoration:none; display:block; padding:.65rem 1rem; border-radius:.5rem; transition:transform .15s ease, background-color .2s ease, color .2s ease; }
        .sidebar a i{ opacity:.85; transition:transform .15s ease; }
        .sidebar a.active, .sidebar a:hover{ background:var(--sidebar-hover); color:#fff; transform:translateX(2px); }
        .content-area{ padding:28px; }
        .card{ border:0; box-shadow:0 8px 22px rgba(16,24,40,.08); border-radius:14px; transition:transform .2s ease, box-shadow .2s ease; }
        .card:hover{ transform:translateY(-2px); box-shadow:0 12px 26px rgba(16,24,40,.12); }
        .table{ --bs-table-bg: transparent; }
        .table tr{ transition:background-color .15s ease; }
        .table tbody tr:hover{ background-color:#f8fafc; }
        .btn-primary{ background:var(--brand-primary); border-color:var(--brand-primary); box-shadow:0 2px 8px rgba(37,99,235,.25); }
        .btn-primary:hover{ background:var(--brand-primary-600); border-color:var(--brand-primary-600); }
        .btn-outline-secondary:hover{ background:#f1f5f9; }
        .form-control, .form-select{ border-radius:12px; border-color:#e2e8f0; transition:box-shadow .15s ease, border-color .15s ease; }
        .form-control:focus, .form-select:focus{ border-color:var(--brand-primary); box-shadow:0 0 0 .25rem rgba(37,99,235,.15); }
        .badge.text-bg-success{ background:var(--success)!important; }
        .badge.text-bg-warning{ background:var(--warning)!important; }
        .badge.text-bg-secondary{ background:var(--secondary)!important; }
        .alert{ border-radius:14px; box-shadow:0 8px 22px rgba(2,6,23,.08); }
        .footer{ color:#98a2b3; font-size:.875rem; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-semibold" href="{{ url('/dashboard') }}">
            <i class="bi bi-grid-1x2 me-2 text-primary"></i>{{ config('app.name', 'Apartman Yönetimi') }}
        </a>
        <div class="ms-auto d-flex align-items-center gap-2">
            @auth
                <span class="text-muted small">{{ auth()->user()->first_name ?? auth()->user()->name }}</span>
                <a class="btn btn-sm btn-primary" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
            @endauth
        </div>
    </div>
    </nav>

<div class="d-flex">
    <aside class="sidebar p-3 d-none d-md-block">
        <div class="brand mb-3">Yönetim Paneli</div>
        <div class="text-white-50 small mb-2 text-uppercase">Genel</div>
        <a href="{{ route('sites.index') }}" class="{{ request()->is('sites*') ? 'active' : '' }}"><i class="bi bi-buildings me-2"></i> Siteler</a>
        <a href="{{ route('blocks.index') }}" class="{{ request()->is('blocks*') ? 'active' : '' }}"><i class="bi bi-diagram-3 me-2"></i> Bloklar</a>
        <a href="#" class="disabled"><i class="bi bi-houses me-2"></i> Apartmanlar</a>
        <a href="#" class="disabled"><i class="bi bi-door-open me-2"></i> Daireler</a>
        <div class="text-white-50 small my-2 text-uppercase">Operasyon</div>
        <a href="#" class="disabled"><i class="bi bi-cash-coin me-2"></i> Finans</a>
        <a href="#" class="disabled"><i class="bi bi-megaphone me-2"></i> Duyurular</a>
    </aside>

    <main class="flex-grow-1">
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Hata:</strong> Lütfen formu kontrol edin.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('content')
        </div>
        <div class="px-4 pb-4 footer">
            © {{ date('Y') }} Apartman Yönetimi
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Uyarıları otomatik kapat (3.5sn)
    window.addEventListener('load', () => {
        const alerts = document.querySelectorAll('.alert');
        setTimeout(() => alerts.forEach(a => new bootstrap.Alert(a).close()), 3500);
    });
</script>
</body>
</html>


