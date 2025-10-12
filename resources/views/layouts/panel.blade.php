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
        .brand-logo{ display:flex; align-items:center; gap:10px; }
        .brand-logo .logo-box{ width:34px; height:34px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; color:#fff; background:linear-gradient(135deg,#2563eb 0%, #22c55e 100%); box-shadow:0 8px 22px rgba(37,99,235,.35); animation: floatY 4s ease-in-out infinite; }
        .brand-logo .brand-text{ font-weight:600; letter-spacing:.2px; background:linear-gradient(90deg,#0ea5e9, #22c55e, #a855f7, #0ea5e9); background-size:200% auto; -webkit-background-clip:text; background-clip:text; color:transparent; animation: shimmer 6s linear infinite; }
        @keyframes shimmer{ 0%{ background-position:0% 50%; } 100%{ background-position:200% 50%; } }
        @keyframes floatY{ 0%,100%{ transform: translateY(0); } 50%{ transform: translateY(-3px); } }
        .sidebar{ width:280px; min-height:100vh; background:var(--sidebar-bg); position:sticky; top:0; }
        .sidebar .brand{ color:#fff; font-weight:600; letter-spacing:.3px; }
        .sidebar a{ color:var(--sidebar-text); text-decoration:none; display:block; padding:.65rem 1rem; border-radius:.5rem; transition:transform .15s ease, background-color .2s ease, color .2s ease; }
        .sidebar a i{ opacity:.85; transition:transform .15s ease; }
        .sidebar a.active, .sidebar a:hover{ background:var(--sidebar-hover); color:#fff; transform:translateX(2px); }
        main{ display:flex; flex-direction:column; min-height:100vh; }
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
        .footer{ color:#98a2b3; font-size:.875rem; margin-top:auto; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid">
        <div class="navbar-brand brand-logo" style="cursor:default; user-select:none;">
            <span class="logo-box"><i class="bi bi-buildings"></i></span>
            <span class="brand-text">Apartman Yönetimi</span>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            @auth
                <div class="dropdown">
                    <button class="btn btn-light d-flex align-items-center gap-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="rounded-circle d-inline-flex justify-content-center align-items-center" style="width:28px;height:28px;background:#e2e8f0;color:#334155;font-weight:600;">
                            {{ strtoupper(Str::substr(auth()->user()->first_name ?? auth()->user()->name,0,1)) }}
                        </span>
                        <span class="small text-muted">{{ auth()->user()->first_name ?? auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="{{ route('account.profile') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="px-3" data-confirm="Çıkış yapılsın mı?">
                                @csrf
                                <button class="btn btn-link dropdown-item px-0"><i class="bi bi-box-arrow-right me-2"></i>Çıkış</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
    </nav>

<div class="d-flex">
    <aside class="sidebar p-3 d-none d-md-block">
        @hasanyrole('admin|site_manager|super_admin')
            <div class="brand mb-3">Yönetim Paneli</div>
            <div class="text-white-50 small mb-2 text-uppercase">Genel</div>
        @else
            @role('resident')
                <div class="brand mb-3">Sakin Paneli</div>
            @endrole
        @endhasanyrole
        @hasanyrole('admin|site_manager|super_admin')
            @role('super_admin')
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Süper yönetici paneli"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            @endrole
            <div class="text-white-50 small my-2 text-uppercase">Kullanıcı Yönetimi</div>
            @role('super_admin')
            <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}"><i class="bi bi-people me-2"></i> Kullanıcılar</a>
            @endrole
            <a href="{{ route('sites.index') }}" class="{{ request()->is('sites*') ? 'active' : '' }}"><i class="bi bi-buildings me-2"></i> Siteler</a>
            <a href="{{ route('blocks.index') }}" class="{{ request()->is('blocks*') ? 'active' : '' }}"><i class="bi bi-diagram-3 me-2"></i> Bloklar</a>
            <a href="{{ route('apartments.index') }}" class="{{ request()->is('apartments*') ? 'active' : '' }}"><i class="bi bi-houses me-2"></i> Apartmanlar</a>
            <a href="{{ route('flats.index') }}" class="{{ request()->is('flats*') ? 'active' : '' }}"><i class="bi bi-door-open me-2"></i> Daireler</a>
            <div class="text-white-50 small my-2 text-uppercase">Sistem Yönetimi</div>
            <a href="{{ route('system.index') }}" class="{{ request()->is('system') && !request()->is('system/*') ? 'active' : '' }}"><i class="bi bi-gear me-2"></i> Sistem Ana Sayfa</a>
            <a href="{{ route('system.settings') }}" class="{{ request()->is('system/settings*') ? 'active' : '' }}"><i class="bi bi-sliders me-2"></i> Sistem Ayarları</a>
            <a href="{{ route('system.backup') }}" class="{{ request()->is('system/backup*') ? 'active' : '' }}"><i class="bi bi-archive me-2"></i> Yedekleme</a>
            <a href="{{ route('system.logs') }}" class="{{ request()->is('system/logs*') ? 'active' : '' }}"><i class="bi bi-file-text me-2"></i> Loglar</a>
            <a href="{{ route('system.cache') }}" class="{{ request()->is('system/cache*') ? 'active' : '' }}"><i class="bi bi-speedometer2 me-2"></i> Cache</a>
            <div class="text-white-50 small my-2 text-uppercase">Operasyon</div>
            <a href="#" class="disabled"><i class="bi bi-cash-coin me-2"></i> Finans</a>
            <a href="#" class="disabled"><i class="bi bi-megaphone me-2"></i> Duyurular</a>
            <a href="{{ route('settlement.index') }}" 
               class="{{ request()->is('settlement*') ? 'active' : '' }}" 
               @if(request()->is('settlement*')) aria-current="page" @endif
               title="Daire yerleşimlerini yönet">
                <i class="bi bi-people me-2"></i> Yerleşim Yönetimi
            </a>
        @endhasanyrole
        @role('resident')
        <a href="{{ route('resident.home') }}" class="{{ request()->routeIs('resident.home') ? 'active' : '' }}"><i class="bi bi-house-heart me-2"></i>Dashboard</a>
        <a href="{{ route('resident.dues') }}" class="{{ request()->routeIs('resident.dues') ? 'active' : '' }}"><i class="bi bi-receipt me-2"></i> Aidatlar ve Ödemeler</a>
        <a href="{{ route('resident.documents') }}" class="{{ request()->routeIs('resident.documents') ? 'active' : '' }}"><i class="bi bi-folder2 me-2"></i> Belgeler</a>
        <a href="{{ route('resident.complaints') }}" class="{{ request()->routeIs('resident.complaints') ? 'active' : '' }}"><i class="bi bi-chat-dots me-2"></i> Başvurular/Şikayetlerim</a>
        <a href="{{ route('resident.announcements') }}" class="{{ request()->routeIs('resident.announcements') ? 'active' : '' }}"><i class="bi bi-megaphone me-2"></i> Duyurularım</a>
        @endrole
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Uyarıları otomatik kapat (3.5sn)
    window.addEventListener('load', () => {
        const alerts = document.querySelectorAll('.alert');
        setTimeout(() => alerts.forEach(a => new bootstrap.Alert(a).close()), 3500);

        // Global SweetAlert confirm handler
        document.body.addEventListener('submit', function(e){
            const form = e.target;
            if (form && form.matches('form[data-confirm]')) {
                e.preventDefault();
                const message = form.getAttribute('data-confirm') || 'Bu işlemi onaylıyor musunuz?';
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    cancelButtonText: 'Vazgeç',
                    confirmButtonColor: '#2563eb'
                }).then((result)=>{ if(result.isConfirmed){ form.submit(); } });
            }
        }, true);

        // Success toast
        const successMsg = document.querySelector('.alert.alert-success');
        if (successMsg) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: successMsg.textContent.trim(),
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        }
    });
    </script>
<script>
    // Uyarıları otomatik kapat (3.5sn)
    window.addEventListener('load', () => {
        const alerts = document.querySelectorAll('.alert');
        setTimeout(() => alerts.forEach(a => new bootstrap.Alert(a).close()), 3500);
    });
    </script>
@stack('scripts')
</body>
</html>


