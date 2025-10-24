@extends('layouts.panel')

@section('content')
<div class="container-fluid px-3">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1 text-dark fw-bold">
                        <i class="bi bi-building me-2 text-primary"></i>
                        Yerleşim Yönetimi
                    </h2>
                    <p class="text-muted mb-0 small">Siteler ve blokları yönetin</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">
                        <i class="bi bi-building me-1"></i>Site Seç
                    </label>
                    <select name="site_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Seçiniz</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ (string)$selectedSiteId === (string)$site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100 btn-sm">
                        <i class="bi bi-eye me-1"></i>Göster
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedSite)
        @php($count = $blocks->count())
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-building me-2"></i>{{ $selectedSite->name }}
                        </h6>
                        <small class="text-muted">Blok Sayısı: {{ $count }}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary">{{ $count }} Blok</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                @if($count > 0)
                    <div class="row g-3">
                        @foreach($blocks as $block)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <a href="{{ route('settlement.block', $block) }}" class="text-decoration-none text-reset d-block">
                                    <div class="card border-0 shadow-sm h-100 block-card">
                                        <div class="card-body p-3 text-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="bi bi-building text-primary" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <h6 class="mb-1 fw-bold text-dark">{{ $block->name }}</h6>
                                            <p class="text-muted mb-0 small">Blok Detayları</p>
                                            <div class="mt-2">
                                                <span class="badge bg-primary small">Görüntüle</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="text-muted">Blok Bulunamadı</h5>
                        <p class="text-muted">Bu siteye ait henüz blok eklenmemiş.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Block card hover effects */
.block-card {
    transition: all 0.3s ease;
}

.block-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

/* Icon background improvements */
.bg-opacity-10 {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}

/* Card improvements */
.card {
    border-radius: 0.75rem;
}

/* Badge improvements */
.badge {
    font-size: 0.75rem;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .block-card {
        margin-bottom: 1rem;
    }
}
</style>
@endpush


