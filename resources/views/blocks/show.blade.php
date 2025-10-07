@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 m-0 d-flex align-items-center gap-2">
                <i class="bi bi-diagram-3"></i>{{ $block->name }}
                <span class="badge text-bg-{{ $statusClassMap[$block->status] ?? 'secondary' }}">{{ $statusLabelMap[$block->status] ?? ucfirst($block->status) }}</span>
            </h1>
            <div class="text-muted small mt-1">Site: {{ $block->site?->name }} @if($block->site?->site_code)• Kod: <span class="badge bg-secondary">{{ $block->site->site_code }}</span>@endif</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('blocks.edit', $block) }}" class="btn btn-outline-primary" title="Bloku düzenle"><i class="bi bi-pencil-square me-1"></i>Düzenle</a>
            <a href="{{ route('blocks.index') }}" class="btn btn-light" title="Bloklara dön"><i class="bi bi-arrow-left me-1"></i>Geri</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-info-circle"></i>Genel Bilgiler</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Durum:</strong> <span class="badge text-bg-{{ $statusClassMap[$block->status] ?? 'secondary' }}">{{ $statusLabelMap[$block->status] ?? ucfirst($block->status) }}</span></p>
                    <p class="mb-2"><strong>Oluşturulma:</strong> {{ $block->created_at?->format('d.m.Y H:i') }}</p>
                    <p class="mb-0"><strong>Güncellenme:</strong> {{ $block->updated_at?->format('d.m.Y H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-grid"></i>Özet</div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $block->total_apartments }}</div>
                            <small class="text-muted">Apartman</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $block->total_floors }}</div>
                            <small class="text-muted">Toplam Kat</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $block->flats_per_floor }}</div>
                            <small class="text-muted">Kat Başına Daire</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $calculatedTotalFlats }}</div>
                            <small class="text-muted">Tahmini Toplam Daire</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


