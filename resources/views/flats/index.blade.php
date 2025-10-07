@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Daireler</h1>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
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
                        @foreach(['empty'=>'Boş','occupied'=>'Dolu','maintenance'=>'Bakım','renovation'=>'Tadilat'] as $k=>$v)
                            <option value="{{ $k }}" {{ $status===$k ? 'selected' : '' }}>{{ $v }}</option>
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
                        @foreach(['1+0','1+1','2+1','3+1','4+1','5+1'] as $t)
                            <option value="{{ $t }}" {{ $flatType===$t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Ara</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
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
                        <tr>
                            <td>{{ $flat->apartment?->block?->site?->name }}</td>
                            <td>{{ $flat->apartment?->block?->name }}</td>
                            <td>{{ $flat->apartment?->name }}</td>
                            <td>{{ $flat->floor_number }}</td>
                            <td>{{ $flat->flat_number }}</td>
                            <td>{{ $flat->flat_type }}</td>
                            <td>
                                @php($label = $flat->status === 'empty' ? 'Boş' : ($flat->status === 'occupied' ? 'Dolu' : ($flat->status==='maintenance'?'Bakım':'Tadilat')))
                                <span class="badge text-bg-{{ $flat->status === 'empty' ? 'secondary' : ($flat->status === 'occupied' ? 'success' : 'warning') }}">{{ $label }}</span>
                            </td>
                            <td>{{ $flat->active_residents_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('settlement.flat', $flat) }}" class="btn btn-sm btn-outline-secondary">Detay</a>
                                <a href="{{ route('settlement.assign.form', $flat) }}" class="btn btn-sm btn-outline-primary">Sakin Ata</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Kayıt bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($flats->hasPages())
            <div class="card-footer">{{ $flats->links() }}</div>
        @endif
    </div>
</div>
@endsection


