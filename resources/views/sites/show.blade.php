@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 m-0 d-flex align-items-center gap-2">
                <i class="bi bi-buildings"></i>{{ $site->name }}
                <span class="badge bg-{{ $statusClassMap[$site->status] ?? 'secondary' }}">{{ ucfirst($site->status) }}</span>
            </h1>
            <div class="text-muted small mt-1">Kod: <span class="badge bg-secondary">{{ $site->site_code }}</span></div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sites.edit', $site) }}" class="btn btn-outline-primary" title="Siteyi düzenle"><i class="bi bi-pencil-square me-1"></i>Düzenle</a>
            <a href="{{ route('sites.index') }}" class="btn btn-light" title="Sitelere dön"><i class="bi bi-arrow-left me-1"></i>Geri</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-info-circle"></i>Genel Bilgiler</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Adres:</strong> {{ $site->address }}</p>
                    @if($site->description)
                        <p class="mb-0"><strong>Açıklama:</strong> {{ $site->description }}</p>
                    @else
                        <p class="text-muted mb-0">Açıklama eklenmemiş.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-grid"></i>Özet</div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $site->total_blocks }}</div>
                            <small class="text-muted">Blok</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $site->total_apartments }}</div>
                            <small class="text-muted">Apartman</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $site->total_floors }}</div>
                            <small class="text-muted">Toplam Kat</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $site->flats_per_floor }}</div>
                            <small class="text-muted">Toplam Daire</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


