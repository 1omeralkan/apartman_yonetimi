@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <h1 class="h5 mb-3 d-flex align-items-center gap-2"><i class="bi bi-person"></i> Hesabım</h1>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">Bilgilerim</div>
                <div class="card-body">
                    <div class="mb-2"><span class="text-muted small">Ad:</span> <span class="fw-semibold">{{ $user->first_name }}</span></div>
                    <div class="mb-2"><span class="text-muted small">Soyad:</span> <span class="fw-semibold">{{ $user->last_name }}</span></div>
                    <div class="mb-2"><span class="text-muted small">E-posta:</span> <span class="fw-semibold">{{ $user->email }}</span></div>
                    <div><span class="text-muted small">Telefon:</span> <span class="fw-semibold">{{ $user->phone ?? '-' }}</span></div>
                    <div class="mt-3"><a href="#" class="btn btn-outline-secondary disabled">Düzenle (yakında)</a></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">Güvenlik</div>
                <div class="card-body">
                    <div class="mb-2 text-muted small">Şifre değiştir, 2FA (yakında)</div>
                    <a href="#" class="btn btn-outline-secondary disabled">Şifre Değiştir</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">Bildirim Tercihleri</div>
                <div class="card-body">
                    <div class="text-muted small">Aidat ve duyuru bildirim tercihleri (yakında)</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">Yasal</div>
                <div class="card-body">
                    <div class="text-muted small">KVKK / iletişim izinleri (yakında)</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



