@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 m-0 d-flex align-items-center gap-2"><i class="bi bi-door-open"></i>{{ $flat->apartment?->block?->site?->name }} / {{ $flat->apartment?->block?->name }} / {{ $flat->apartment?->name }} / Daire {{ $flat->flat_number }}</h1>
        </div>
        <div>
            <a href="{{ route('settlement.apartment', $flat->apartment_id) }}" class="btn btn-light" title="Apartman görünümüne dön"><i class="bi bi-arrow-left me-1"></i>Geri</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-info-circle"></i>Daire Bilgileri</div>
                <div class="card-body">
                    <div class="mb-2"><strong>Kat:</strong> {{ $flat->floor_number }}</div>
                    <div class="mb-2"><strong>Daire No:</strong> {{ $flat->flat_number }}</div>
                    <div class="mb-2"><strong>Tip:</strong> {{ $flat->flat_type }}</div>
                    <div class="mb-2"><strong>Durum:</strong> <span class="badge text-bg-{{ $statusClassMap[$flat->status] ?? 'secondary' }}">{{ $statusOptions[$flat->status] ?? ucfirst($flat->status) }}</span></div>
                    <div class="mb-2"><strong>Apartman:</strong> {{ $flat->apartment?->name }}</div>
                    <div class="mb-0"><strong>Blok / Site:</strong> {{ $flat->apartment?->block?->name }} / {{ $flat->apartment?->block?->site?->name }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="d-flex align-items-center gap-2"><i class="bi bi-people"></i>Sakinler</span>
                    <a href="{{ route('settlement.assign.form', $flat) }}" class="btn btn-sm btn-primary"><i class="bi bi-person-plus me-1"></i>Sakin Ata</a>
                </div>
                <div class="card-body">
                    @forelse($flat->residents as $res)
                        <div class="border rounded p-2 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $res->user?->first_name }} {{ $res->user?->last_name }}</div>
                                    <div class="text-muted small">{{ $res->user?->email }} @if($res->user?->phone) • {{ $res->user->phone }} @endif</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="badge {{ ($res->status==='active') ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $residentStatusMap[$res->status] ?? $res->status }}</span>
                                    <span class="badge text-bg-info">{{ $residentTypeMap[$res->resident_type] ?? $res->resident_type }}</span>
                                    <form action="{{ route('settlement.unassign', $res) }}" method="POST" data-confirm="Sakini kaldırmak istediğinize emin misiniz?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Sakini kaldır"><i class="bi bi-person-dash me-1"></i>Kaldır</button>
                                    </form>
                                </div>
                            </div>
                            <div class="mt-2 small text-muted">
                                <span>Giriş: {{ optional($res->move_in_date)->format('d.m.Y') }}</span>
                                @if($res->move_out_date)
                                    <span> • Çıkış: {{ optional($res->move_out_date)->format('d.m.Y') }}</span>
                                @endif
                                @if(!is_null($res->rent_amount))
                                    <span> • Kira: ₺{{ number_format((float)$res->rent_amount, 2, ',', '.') }}</span>
                                @endif
                            </div>
                            @if($res->notes)
                                <div class="mt-1 small">Not: {{ $res->notes }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-muted">Henüz sakin yok.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


