@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Apartmanlar</h1>
        <a href="{{ route('apartments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Yeni Apartman</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2" aria-label="Apartman filtre formu">
                <div class="col-md-4">
                    <select name="site_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tüm Siteler</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ (string)($selectedSiteId ?? '') === (string)$site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="block_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tüm Bloklar</option>
                        @foreach($blocks as $block)
                            <option value="{{ $block->id }}" {{ (string)($selectedBlockId ?? '') === (string)$block->id ? 'selected' : '' }}>{{ $block->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-none d-md-block">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Uygula</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('apartments.index') }}" class="btn btn-light w-100"><i class="bi bi-x-circle me-1"></i>Temizle</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0" aria-label="Apartmanlar tablosu">
                <caption class="px-3 pt-3 text-muted small">Kayıtlı apartman listesi. Satıra tıklayarak detay sayfasına gidebilirsiniz.</caption>
                <thead>
                <tr>
                    <th>Ad</th>
                    <th>Site</th>
                    <th>Blok</th>
                    <th>Durum</th>
                    <th>Kat</th>
                    <th>Kat Başına Daire</th>
                    <th>Toplam Daire</th>
                    <th class="text-end">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse($apartments as $apartment)
                    <tr class="table-row" onclick="window.location='{{ route('apartments.show', $apartment) }}'" style="cursor:pointer;">
                        <td class="fw-semibold"><a href="{{ route('apartments.show', $apartment) }}" class="text-decoration-none">{{ $apartment->name }}</a></td>
                        <td>{{ $apartment->site?->name }}</td>
                        <td>{{ $apartment->block?->name }}</td>
                        <td>
                            <span class="badge text-bg-{{ $statusClassMap[$apartment->status] ?? 'secondary' }}">{{ $statusLabelMap[$apartment->status] ?? ucfirst($apartment->status) }}</span>
                        </td>
                        <td>{{ number_format($apartment->total_floors) }}</td>
                        <td>{{ number_format($apartment->flats_per_floor) }}</td>
                        <td>{{ number_format($apartment->total_flats) }}</td>
                        <td class="text-end">
                            <a href="{{ route('apartments.edit', $apartment) }}" class="btn btn-sm btn-outline-secondary" title="Apartmanı düzenle" onclick="event.stopPropagation();"><i class="bi bi-pencil-square me-1"></i>Düzenle</a>
                            <form action="{{ route('apartments.destroy', $apartment) }}" method="POST" class="d-inline" data-confirm="Apartmanı silmek istediğinize emin misiniz? İlgili daireler etkilenebilir." onclick="event.stopPropagation();">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Apartmanı sil" onclick="event.stopPropagation();"><i class="bi bi-trash3 me-1"></i>Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <div class="d-inline-flex flex-column align-items-center gap-2">
                                <i class="bi bi-houses fs-1 text-secondary"></i>
                                <div>Kayıt bulunamadı.</div>
                                <a href="{{ route('apartments.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Yeni Apartman Oluştur</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($apartments->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    {{ $apartments->firstItem() }}–{{ $apartments->lastItem() }} / {{ $apartments->total() }} kayıt
                </div>
                <div>{{ $apartments->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection


