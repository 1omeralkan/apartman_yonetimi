@extends('layouts.panel')

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Siteler</h1>
        <a href="{{ route('sites.create') }}" class="btn btn-primary">Yeni Site</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" aria-label="Siteler tablosu">
                <caption class="px-3 pt-3 text-muted small">Kayıtlı site listesi. Satıra tıklayarak detay sayfasına gidebilirsiniz.</caption>
                <thead class="table-light">
                    <tr>
                        <th>Ad</th>
                        <th>Kod</th>
                        <th>Durum</th>
                        <th>Blok</th>
                        <th>Apartman</th>
                        <th>Toplam Kat</th>
                        <th>Kat Başına Daire</th>
                        <th>Toplam Daire</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sites as $site)
                        <tr class="table-row" onclick="window.location='{{ route('sites.show', $site) }}'" style="cursor:pointer;">
                            <td class="fw-semibold"><a href="{{ route('sites.show', $site) }}" class="text-decoration-none">{{ $site->name }}</a></td>
                            <td><span class="badge bg-secondary">{{ $site->site_code }}</span></td>
                            <td>
                                <span class="badge bg-{{ $statusClassMap[$site->status] ?? 'secondary' }}">{{ ucfirst($site->status) }}</span>
                            </td>
                            <td>{{ $site->total_blocks }}</td>
                            <td>{{ $site->total_apartments }}</td>
                            <td>{{ $site->total_floors }}</td>
                            <td>{{ $site->flats_per_floor }}</td>
                            <td>{{ number_format((int) $site->total_floors * (int) $site->flats_per_floor) }}</td>
                            <td class="text-end">
                                <a href="{{ route('sites.edit', $site) }}" class="btn btn-sm btn-outline-primary" title="Siteyi düzenle" onclick="event.stopPropagation();"><i class="bi bi-pencil-square me-1"></i>Düzenle</a>
                                <form action="{{ route('sites.destroy', $site) }}" method="POST" class="d-inline" data-confirm="Siteyi silmek istediğinize emin misiniz? Bu işlem geri alınamaz." onclick="event.stopPropagation();">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Siteyi sil" onclick="event.stopPropagation();"><i class="bi bi-trash3 me-1"></i>Sil</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <div class="d-inline-flex flex-column align-items-center gap-2">
                                    <i class="bi bi-buildings fs-1 text-secondary"></i>
                                    <div>Henüz site eklenmemiş.</div>
                                    <a href="{{ route('sites.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Yeni Site Oluştur</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                @if($sites->total() > 0)
                    {{ $sites->firstItem() }}–{{ $sites->lastItem() }} / {{ $sites->total() }} kayıt
                @else
                    Kayıt bulunamadı
                @endif
            </div>
            <div>
                {{ $sites->links() }}
            </div>
        </div>
    </div>
</div>
@endsection


