@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 m-0">{{ $site->name }}</h1>
            <div class="text-muted">Kod: {{ $site->site_code }} • Durum: {{ ucfirst($site->status) }}</div>
        </div>
        <div>
            <a href="{{ route('sites.edit', $site) }}" class="btn btn-outline-primary">Düzenle</a>
            <a href="{{ route('sites.index') }}" class="btn btn-light">Geri</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header">Genel Bilgiler</div>
                <div class="card-body">
                    <p><strong>Adres:</strong> {{ $site->address }}</p>
                    @if($site->description)
                        <p class="mb-0"><strong>Açıklama:</strong> {{ $site->description }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header">Özet</div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h5 mb-0">{{ $site->total_blocks }}</div>
                            <small class="text-muted">Blok</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h5 mb-0">{{ $site->total_apartments }}</div>
                            <small class="text-muted">Apartman</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $site->total_floors }}</div>
                            <small class="text-muted">Toplam Kat</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $site->flats_per_floor }}</div>
                            <small class="text-muted">Kat Başına Daire</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


