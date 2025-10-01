@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 m-0">{{ $block->name }}</h1>
            <div class="text-muted">Site: {{ $block->site?->name }} @if($block->site?->site_code)• Kod: {{ $block->site->site_code }} @endif</div>
        </div>
        <div>
            <a href="{{ route('blocks.edit', $block) }}" class="btn btn-outline-primary">Düzenle</a>
            <a href="{{ route('blocks.index') }}" class="btn btn-light">Geri</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header">Genel Bilgiler</div>
                <div class="card-body">
                    <p><strong>Durum:</strong>
                        @php($label = $block->status === 'active' ? 'Aktif' : ($block->status === 'inactive' ? 'Pasif' : 'Bakımda'))
                        <span class="badge text-bg-{{ $block->status === 'active' ? 'success' : ($block->status === 'inactive' ? 'secondary' : 'warning') }}">{{ $label }}</span>
                    </p>
                    <p><strong>Oluşturulma:</strong> {{ $block->created_at?->format('d.m.Y H:i') }}</p>
                    <p class="mb-0"><strong>Güncellenme:</strong> {{ $block->updated_at?->format('d.m.Y H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header">Özet</div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h5 mb-0">{{ $block->total_apartments }}</div>
                            <small class="text-muted">Apartman</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h5 mb-0">{{ $block->total_floors }}</div>
                            <small class="text-muted">Toplam Kat</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $block->flats_per_floor }}</div>
                            <small class="text-muted">Kat Başına Daire</small>
                        </div>
                        <div class="col-6">
                            @php($totalFlats = (int) $block->total_floors * (int) $block->flats_per_floor)
                            <div class="h5 mb-0">{{ $totalFlats }}</div>
                            <small class="text-muted">Tahmini Toplam Daire</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


