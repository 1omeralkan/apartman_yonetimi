@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 m-0">{{ $block->site?->name }} / {{ $block->name }}</h1>
            <div class="text-muted">Apartmanlar</div>
        </div>
        <div>
            <a href="{{ route('settlement.index', ['site_id' => $block->site_id]) }}" class="btn btn-light">Geri</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2">
                @forelse($apartments as $apartment)
                    <div class="col-3">
                        <a href="{{ route('settlement.apartment', $apartment) }}" class="text-decoration-none text-reset d-block">
                            <div class="p-1 rounded border position-relative" style="background:#f6f8fb; border-color:#e2e8f0">
                                <div class="ratio ratio-1x1 rounded" style="background:#e9eef6;">
                                    <div class="d-flex flex-column justify-content-center align-items-center h-100 px-1 text-center">
                                        <div class="fw-semibold" style="font-size:.95rem; line-height:1.15">{{ $apartment->name }}</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-4">Bu blokta apartman bulunamadı.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection


