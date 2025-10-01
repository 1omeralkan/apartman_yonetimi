@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Apartmanlar</h1>
        <a href="{{ route('apartments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Yeni Apartman</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <select name="site_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tüm Siteler</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="block_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tüm Bloklar</option>
                        @foreach($blocks as $block)
                            <option value="{{ $block->id }}" {{ request('block_id') == $block->id ? 'selected' : '' }}>{{ $block->name }}</option>
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
                    <th>Blok</th>
                    <th>Durum</th>
                    <th>Kat</th>
                    <th>Toplam Daire</th>
                    <th class="text-end">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse($apartments as $apartment)
                    <tr>
                        <td class="fw-semibold"><a href="{{ route('apartments.show', $apartment) }}" class="text-decoration-none">{{ $apartment->name }}</a></td>
                        <td>{{ $apartment->site?->name }}</td>
                        <td>{{ $apartment->block?->name }}</td>
                        <td>
                            @php($label = $apartment->status === 'active' ? 'Aktif' : ($apartment->status === 'inactive' ? 'Pasif' : 'Bakımda'))
                            <span class="badge text-bg-{{ $apartment->status === 'active' ? 'success' : ($apartment->status === 'inactive' ? 'secondary' : 'warning') }}">{{ $label }}</span>
                        </td>
                        <td>{{ number_format($apartment->total_floors) }}</td>
                        <td>{{ number_format($apartment->total_flats) }}</td>
                        <td class="text-end">
                            <a href="{{ route('apartments.edit', $apartment) }}" class="btn btn-sm btn-outline-secondary">Düzenle</a>
                            <form action="{{ route('apartments.destroy', $apartment) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($apartments->hasPages())
            <div class="card-footer">{{ $apartments->links() }}</div>
        @endif
    </div>
</div>
@endsection


