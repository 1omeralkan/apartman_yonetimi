@extends('layouts.panel')

@section('content')
<div class="container-fluid px-3">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1 text-dark fw-bold">
                        <i class="bi bi-house me-2 text-primary"></i>
                        {{ $apartment->block?->site?->name }} / {{ $apartment->block?->name }} / {{ $apartment->name }}
                    </h2>
                    <p class="text-muted mb-0 small">Kat Planı ({{ $apartment->total_floors }} kat, {{ $flatsPerFloor }} daire/kat)</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('settlement.block', $apartment->block) }}" class="btn btn-outline-secondary btn-sm">
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
                        <i class="bi bi-house me-2"></i>{{ $apartment->name }} Kat Planı
                    </h6>
                    <small class="text-muted">Toplam {{ $flatsByFloor->flatten()->count() }} daire</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary">{{ $apartment->total_floors }} Kat</span>
                    <span class="badge bg-info">{{ $flatsPerFloor }} Daire/Kat</span>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            @if($flatsByFloor->count() > 0)
                @foreach($flatsByFloor as $floor => $flats)
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-pill px-3 py-2 me-3">
                                <span class="fw-bold text-primary">{{ $floor }}. Kat</span>
                            </div>
                            <div class="flex-grow-1 border-top border-2 border-primary" style="opacity: 0.3;"></div>
                        </div>
                        <div class="row g-3">
                            @foreach($flats->sortBy('flat_number') as $flat)
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card border-0 shadow-sm h-100 flat-card">
                                        <div class="card-body p-3 text-center">
                                            <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="bi bi-house text-warning" style="font-size: 1.25rem;"></i>
                                            </div>
                                            <h6 class="mb-1 fw-bold text-dark">Daire {{ $flat->flat_number }}</h6>
                                            <p class="text-muted mb-2 small">{{ $flat->flat_type }}</p>
                                            <div class="mb-3">
                                                @if($flat->status == 'empty')
                                                    <span class="badge bg-secondary small">Boş</span>
                                                @elseif($flat->status == 'occupied')
                                                    <span class="badge bg-success small">Dolu</span>
                                                @else
                                                    <span class="badge bg-warning small">Beklemede</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('settlement.assign.form', $flat) }}" 
                                               class="btn btn-sm btn-outline-primary w-100">
                                                <i class="bi bi-person-plus me-1"></i>Sakin Ata
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-house text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">Daire Bulunamadı</h5>
                    <p class="text-muted">Bu apartmanda henüz daire eklenmemiş.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Flat card hover effects */
.flat-card {
    transition: all 0.3s ease;
}

.flat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

/* Icon background improvements */
.bg-opacity-10 {
    background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
}

/* Card improvements */
.card {
    border-radius: 0.75rem;
}

/* Badge improvements */
.badge {
    font-size: 0.75rem;
}

/* Floor separator improvements */
.border-top {
    border-top: 2px solid var(--bs-primary) !important;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .flat-card {
        margin-bottom: 1rem;
    }
    
    .col-sm-6 {
        margin-bottom: 1rem;
    }
}

/* Button improvements */
.btn-outline-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 123, 255, 0.25);
}
</style>
@endpush


