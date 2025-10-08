@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Bloklar</h1>
        <a href="{{ route('blocks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Yeni Blok
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2" aria-label="Blok filtre formu">
                <div class="col-md-4">
                    <select name="site_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tüm Siteler</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ (string)($selectedSiteId ?? '') === (string)$site->id ? 'selected' : '' }}>
                                {{ $site->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-none d-md-block">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Uygula</button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('blocks.index') }}" class="btn btn-light w-100"><i class="bi bi-x-circle me-1"></i>Temizle</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0" aria-label="Bloklar tablosu">
                <caption class="px-3 pt-3 text-muted small">Kayıtlı blok listesi. Satıra tıklayarak detay sayfasına gidebilirsiniz.</caption>
                <thead>
                <tr>
                    <th>Ad</th>
                    <th>Site</th>
                    <th>Toplam Apartman</th>
                    <th>Toplam Kat</th>
                    <th>Kat Başına Daire</th>
                    <th>Toplam Daire</th>
                    <th>Durum</th>
                    <th>Oluşturulma</th>
                    <th class="text-end">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse($blocks as $block)
                    <tr class="table-row" onclick="window.location='{{ route('blocks.show', $block) }}'" style="cursor:pointer;">
                        <td class="fw-semibold"><a href="{{ route('blocks.show', $block) }}" class="text-decoration-none">{{ $block->name }}</a></td>
                        <td>
                            {{ $block->site?->name }}
                            @if($block->site?->site_code)
                                <span class="text-muted small">({{ $block->site->site_code }})</span>
                            @endif
                        </td>
                        <td>{{ number_format($block->total_apartments) }}</td>
                        <td>{{ number_format($block->total_floors) }}</td>
                        <td>{{ number_format($block->flats_per_floor) }}</td>
                        <td>{{ number_format((int) $block->total_floors * (int) $block->flats_per_floor) }}</td>
                        <td>
                            <span class="badge text-bg-{{ $statusClassMap[$block->status] ?? 'secondary' }}">{{ $statusLabelMap[$block->status] ?? ucfirst($block->status) }}</span>
                        </td>
                        <td>{{ $block->created_at?->format('d.m.Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('blocks.edit', $block) }}" class="btn btn-sm btn-outline-secondary" title="Bloku düzenle" onclick="event.stopPropagation();"><i class="bi bi-pencil-square me-1"></i>Düzenle</a>
                            <form action="{{ route('blocks.destroy', $block) }}" method="POST" class="d-inline" data-confirm="Bloku silmek istediğinize emin misiniz? Bu işlem geri alınamaz." onclick="event.stopPropagation();">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Bloku sil" onclick="event.stopPropagation();"><i class="bi bi-trash3 me-1"></i>Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <div class="d-inline-flex flex-column align-items-center gap-2">
                                <i class="bi bi-diagram-3 fs-1 text-secondary"></i>
                                <div>Kayıt bulunamadı.</div>
                                <a href="{{ route('blocks.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Yeni Blok Oluştur</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($blocks->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    {{ $blocks->firstItem() }}–{{ $blocks->lastItem() }} / {{ $blocks->total() }} kayıt
                </div>
                <div>{{ $blocks->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection


