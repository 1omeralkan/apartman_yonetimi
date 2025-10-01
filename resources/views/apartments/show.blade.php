@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 m-0">{{ $apartment->name }}</h1>
            <div class="text-muted">Site: {{ $apartment->site?->name }} • Blok: {{ $apartment->block?->name }}</div>
        </div>
        <div>
            <a href="{{ route('apartments.edit', $apartment) }}" class="btn btn-outline-primary">Düzenle</a>
            <a href="{{ route('apartments.index') }}" class="btn btn-light">Geri</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header">Genel Bilgiler</div>
                <div class="card-body">
                    @php($label = $apartment->status === 'active' ? 'Aktif' : ($apartment->status === 'inactive' ? 'Pasif' : 'Bakımda'))
                    <p><strong>Durum:</strong> <span class="badge text-bg-{{ $apartment->status === 'active' ? 'success' : ($apartment->status === 'inactive' ? 'secondary' : 'warning') }}">{{ $label }}</span></p>
                    <p><strong>Adres:</strong> {{ $apartment->address }}</p>
                    <p><strong>Asansör:</strong> {{ $apartment->has_elevator ? 'Var' : 'Yok' }}</p>
                    <p class="mb-0"><strong>Otopark:</strong> {{ $apartment->has_parking ? 'Var' : 'Yok' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header">Özet</div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h5 mb-0">{{ $apartment->total_floors }}</div>
                            <small class="text-muted">Toplam Kat</small>
                        </div>
                        <div class="col-6 mb-3">
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


