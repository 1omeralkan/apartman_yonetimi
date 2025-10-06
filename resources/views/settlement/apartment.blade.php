@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 m-0">{{ $apartment->block?->site?->name }} / {{ $apartment->block?->name }} / {{ $apartment->name }}</h1>
            <div class="text-muted">Kat Planı ({{ $apartment->total_floors }} kat, {{ $flatsPerFloor }} daire/kat)</div>
        </div>
        <div>
            <a href="{{ route('settlement.block', $apartment->block) }}" class="btn btn-light">Geri</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @forelse($flatsByFloor as $floor => $flats)
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge text-bg-secondary me-2" style="min-width:64px">{{ $floor }}. Kat</div>
                        <div class="flex-grow-1 border-top" style="opacity:.5"></div>
                    </div>
                    <div class="row g-2">
                        @foreach($flats->sortBy('flat_number') as $flat)
                            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                <div class="rounded border p-2 text-center h-100" style="background:#f8fafc; border-color:#e2e8f0">
                                    <div class="fw-semibold">Daire {{ $flat->flat_number }}</div>
                                    <div class="text-muted small">{{ $flat->flat_type }}</div>
                                    <div class="small mt-1">
                                        <span class="badge {{ $flat->status==='empty' ? 'text-bg-secondary' : ($flat->status==='occupied' ? 'text-bg-success' : 'text-bg-warning') }}">{{ $flat->status }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">Daire bulunamadı.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection


