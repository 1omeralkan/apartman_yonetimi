@extends('layouts.panel')

@section('content')
<div class="container-fluid px-3">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1 text-dark fw-bold">
                        <i class="bi bi-building me-2 text-primary"></i>
                        {{ $block->site?->name }} / {{ $block->name }}
                    </h2>
                    <p class="text-muted mb-0 small">Apartmanlar</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('settlement.index', ['site_id' => $block->site_id]) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Geri
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-building me-2"></i>{{ $block->name }} Apartmanları
                    </h6>
                    <small class="text-muted">Toplam {{ $apartments->count() }} apartman</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary">{{ $apartments->count() }} Apartman</span>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            @if($apartments->count() > 0)
                <div class="row g-3">
                    @foreach($apartments as $apartment)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <a href="{{ route('settlement.apartment', $apartment) }}" class="text-decoration-none text-reset d-block">
                                <div class="card border-0 shadow-sm h-100 apartment-card">
                                    <div class="card-body p-3 text-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-building text-success" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <h6 class="mb-1 fw-bold text-dark">{{ $apartment->name }}</h6>
                                        <p class="text-muted mb-0 small">Apartman Detayları</p>
                                        <div class="mt-2">
                                            <span class="badge bg-success small">Görüntüle</span>
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
                    <h5 class="text-muted">Apartman Bulunamadı</h5>
                    <p class="text-muted">Bu blokta henüz apartman eklenmemiş.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Apartment card hover effects */
.apartment-card {
    transition: all 0.3s ease;
}

.apartment-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

/* Icon background improvements */
.bg-opacity-10 {
    background-color: rgba(var(--bs-success-rgb), 0.1) !important;
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
    .apartment-card {
        margin-bottom: 1rem;
    }
}
</style>
@endpush


