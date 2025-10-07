@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Daireler</h1>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2" aria-label="Daire filtre formu">
                <div class="col-md-3">
                    <label class="form-label">Site</label>
                    <select name="site_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tümü</option>
                        @foreach($sites as $s)
                            <option value="{{ $s->id }}" {{ (string)$siteId === (string)$s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Blok</label>
                    <select name="block_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tümü</option>
                        @foreach($blocks as $b)
                            <option value="{{ $b->id }}" {{ (string)$blockId === (string)$b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Apartman</label>
                    <select name="apartment_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tümü</option>
                        @foreach($apartments as $a)
                            <option value="{{ $a->id }}" {{ (string)$apartmentId === (string)$a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tümü</option>
                        @foreach(($statusOptions ?? []) as $k=>$v)
                            <option value="{{ $k }}" {{ (string)($status ?? '') === (string)$k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kat Min</label>
                    <input type="number" name="floor_min" value="{{ $floorMin }}" class="form-control" placeholder="0" />
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kat Max</label>
                    <input type="number" name="floor_max" value="{{ $floorMax }}" class="form-control" placeholder="999" />
                </div>
                <div class="col-md-2">
                    <label class="form-label">Daire No</label>
                    <input type="number" name="flat_no" value="{{ $flatNo }}" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Daire Tipi</label>
                    <select name="flat_type" class="form-select">
                        <option value="">Tümü</option>
                        @foreach(($flatTypes ?? []) as $t)
                            <option value="{{ $t }}" {{ (string)($flatType ?? '') === (string)$t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Ara</button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('flats.index') }}" class="btn btn-light w-100"><i class="bi bi-x-circle me-1"></i>Temizle</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0" aria-label="Daireler tablosu">
                <caption class="px-3 pt-3 text-muted small">Kayıtlı daire listesi. Satıra tıklayarak detay sayfasına gidebilirsiniz.</caption>
                <thead>
                    <tr>
                        <th>Site</th>
                        <th>Blok</th>
                        <th>Apartman</th>
                        <th>Kat</th>
                        <th>No</th>
                        <th>Tip</th>
                        <th>Durum</th>
                        <th>Aktif Sakin</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flats as $flat)
                        <tr class="table-row" onclick="window.location='{{ route('settlement.flat', $flat) }}'" style="cursor:pointer;">
                            <td>{{ $flat->apartment?->block?->site?->name }}</td>
                            <td>{{ $flat->apartment?->block?->name }}</td>
                            <td>{{ $flat->apartment?->name }}</td>
                            <td>{{ $flat->floor_number }}</td>
                            <td>{{ $flat->flat_number }}</td>
                            <td>{{ $flat->flat_type }}</td>
                            <td>
                                <span class="badge text-bg-{{ $statusClassMap[$flat->status] ?? 'secondary' }}">
                                    {{ ($statusOptions[$flat->status] ?? null) ?? ucfirst($flat->status) }}
                                </span>
                            </td>
                            <td>{{ $flat->active_residents_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('settlement.flat', $flat) }}" class="btn btn-sm btn-outline-secondary" title="Daire detayı"><i class="bi bi-box-arrow-up-right me-1"></i>Detay</a>
                                <a href="{{ route('settlement.assign.form', $flat) }}" class="btn btn-sm btn-outline-primary" title="Sakin ata"><i class="bi bi-person-plus me-1"></i>Sakin Ata</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <div class="d-inline-flex flex-column align-items-center gap-2">
                                    <i class="bi bi-door-open fs-1 text-secondary"></i>
                                    <div>Kayıt bulunamadı.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($flats->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    {{ $flats->firstItem() }}–{{ $flats->lastItem() }} / {{ $flats->total() }} kayıt
                </div>
                <div>{{ $flats->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection


