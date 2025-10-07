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
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ad</th>
                        <th>Kod</th>
                        <th>Durum</th>
                        <th>Blok</th>
                        <th>Apartman</th>
                        <th>Kat/Daire</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sites as $site)
                        <tr>
                            <td><a href="{{ route('sites.show', $site) }}" class="text-decoration-none">{{ $site->name }}</a></td>
                            <td><span class="badge bg-secondary">{{ $site->site_code }}</span></td>
                            <td>
                                @php $map = ['active' => 'success','inactive' => 'secondary','maintenance' => 'warning']; @endphp
                                <span class="badge bg-{{ $map[$site->status] ?? 'secondary' }}">{{ ucfirst($site->status) }}</span>
                            </td>
                            <td>{{ $site->total_blocks }}</td>
                            <td>{{ $site->total_apartments }}</td>
                            <td>{{ $site->total_floors }} / {{ $site->flats_per_floor }}</td>
                            <td class="text-end">
                                <a href="{{ route('sites.edit', $site) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                <form action="{{ route('sites.destroy', $site) }}" method="POST" class="d-inline" data-confirm="Siteyi silmek istediğinize emin misiniz? Bu işlem geri alınamaz.">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Henüz site eklenmemiş.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $sites->links() }}
        </div>
    </div>
</div>
@endsection


