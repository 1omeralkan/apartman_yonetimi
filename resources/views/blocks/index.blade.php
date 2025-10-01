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
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <select name="site_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tüm Siteler</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>
                                {{ $site->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>Ad</th>
                    <th>Site</th>
                    <th>Apartman (Toplam)</th>
                    <th>Toplam Kat</th>
                    <th>Kat/Daire</th>
                    <th>Durum</th>
                    <th>Oluşturulma</th>
                    <th class="text-end">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse($blocks as $block)
                    <tr>
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
                        <td>
                            @php($label = $block->status === 'active' ? 'Aktif' : ($block->status === 'inactive' ? 'Pasif' : 'Bakımda'))
                            <span class="badge text-bg-{{ $block->status === 'active' ? 'success' : ($block->status === 'inactive' ? 'secondary' : 'warning') }}">{{ $label }}</span>
                        </td>
                        <td>{{ $block->created_at?->format('d.m.Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('blocks.edit', $block) }}" class="btn btn-sm btn-outline-secondary">Düzenle</a>
                            <form action="{{ route('blocks.destroy', $block) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($blocks->hasPages())
            <div class="card-footer">{{ $blocks->links() }}</div>
        @endif
    </div>
</div>
@endsection


