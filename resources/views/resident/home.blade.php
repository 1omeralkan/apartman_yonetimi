@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 m-0 d-flex align-items-center gap-2"><i class="bi bi-person-home"></i> Sakin Paneli</h1>
        <div class="d-none d-md-flex gap-2">
            <a href="{{ route('resident.dues') }}" class="btn btn-outline-secondary"><i class="bi bi-receipt me-1"></i>Aidatlar</a>
            <a href="{{ route('resident.complaints') }}" class="btn btn-outline-secondary"><i class="bi bi-chat-dots me-1"></i>Şikayetlerim</a>
        </div>
    </div>

    @if($resident)
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Site</div>
                    <div class="fw-semibold text-truncate" title="{{ $resident->flat?->apartment?->block?->site?->name }}">{{ $resident->flat?->apartment?->block?->site?->name }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Blok</div>
                    <div class="fw-semibold">{{ $resident->flat?->apartment?->block?->name }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Apartman</div>
                    <div class="fw-semibold">{{ $resident->flat?->apartment?->name }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Kat / Daire</div>
                    <div class="fw-semibold">{{ $resident->flat?->floor_number }} / {{ $resident->flat?->flat_number }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="text-muted small">Durum</span>
                        <span class="badge text-bg-{{ ($resident->status==='active') ? 'success' : 'secondary' }}">{{ $resident->status==='active' ? 'Aktif' : 'Pasif' }}</span>
                    </div>
                    @if($resident->move_in_date)
                        <div class="text-muted small">Giriş Tarihi: <span class="fw-semibold">{{ optional($resident->move_in_date)->format('d.m.Y') }}</span></div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="row small">
                        <div class="col-6"><span class="text-muted">Ad Soyad:</span> <span class="fw-semibold">{{ $resident->user?->first_name }} {{ $resident->user?->last_name }}</span></div>
                        <div class="col-6"><span class="text-muted">E-posta:</span> <span class="fw-semibold">{{ $resident->user?->email }}</span></div>
                        @if($resident->user?->phone)
                        <div class="col-6 mt-1"><span class="text-muted">Telefon:</span> <span class="fw-semibold">{{ $resident->user?->phone }}</span></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-receipt"></i> Kısa Özet</div>
                <div class="card-body small text-muted">
                    Yakında burada aidat borç özetiniz, en yakın son ödeme tarihi ve son ödemeniz görünecek.
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-lightning-charge"></i> Hızlı İşlemler</div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <a href="{{ route('resident.dues') }}" class="btn btn-primary"><i class="bi bi-credit-card me-1"></i>Aidatları Gör</a>
                    <a href="{{ route('resident.complaints') }}" class="btn btn-outline-secondary"><i class="bi bi-plus-circle me-1"></i>Yeni Şikayet</a>
                    <a href="{{ route('resident.announcements') }}" class="btn btn-outline-secondary"><i class="bi bi-megaphone me-1"></i>Duyurular</a>
                    <a href="{{ route('resident.documents') }}" class="btn btn-outline-secondary"><i class="bi bi-folder2 me-1"></i>Belgeler</a>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-muted">
                Herhangi bir daireye atanmış aktif sakin kaydınız bulunmuyor.
            </div>
        </div>
    @endif
</div>
@endsection



