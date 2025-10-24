@extends('layouts.panel')

@section('content')
<div class="container-fluid px-3">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1 text-dark fw-bold">
                        <i class="bi bi-file-text me-2 text-primary"></i>
                        Log Yönetimi
                    </h2>
                    <p class="text-muted mb-0 small">Sistem loglarını görüntüleyin, analiz edin ve yönetin</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('system.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>
                        Geri Dön
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-file-text text-muted mb-3" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mb-2">Log Yönetimi</h5>
                    <p class="text-muted mb-0">Bu sayfa henüz geliştirilme aşamasındadır.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection