@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 m-0 d-flex align-items-center gap-2">
                <i class="bi bi-building"></i>{{ $apartment->name }}
                <span class="badge text-bg-{{ $statusClassMap[$apartment->status] ?? 'secondary' }}">{{ $statusLabelMap[$apartment->status] ?? ucfirst($apartment->status) }}</span>
            </h1>
            <div class="text-muted small mt-1">Site: {{ $apartment->site?->name }} • Blok: {{ $apartment->block?->name }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('apartments.edit', $apartment) }}" class="btn btn-outline-primary" title="Apartmanı düzenle"><i class="bi bi-pencil-square me-1"></i>Düzenle</a>
            <a href="{{ route('apartments.index') }}" class="btn btn-light" title="Apartmanlara dön"><i class="bi bi-arrow-left me-1"></i>Geri</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-info-circle"></i>Genel Bilgiler</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Adres:</strong> {{ $apartment->address }}</p>
                    <p class="mb-2"><strong>Asansör:</strong> {{ $apartment->has_elevator ? 'Var' : 'Yok' }}</p>
                    <p class="mb-0"><strong>Otopark:</strong> {{ $apartment->has_parking ? 'Var' : 'Yok' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-grid"></i>Özet</div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $apartment->total_floors }}</div>
                            <small class="text-muted">Toplam Kat</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $apartment->flats_per_floor }}</div>
                            <small class="text-muted">Kat Başına Daire</small>
                        </div>
                        <div class="col-12">
                            <div class="h5 mb-0">{{ $apartment->total_flats }}</div>
                            <small class="text-muted">Toplam Daire</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


