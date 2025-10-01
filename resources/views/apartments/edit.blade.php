@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Apartman Düzenle</h1>
        <a href="{{ route('apartments.index') }}" class="btn btn-light">Geri</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('apartments.update', $apartment) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Site</label>
                    <select name="site_id" class="form-select @error('site_id') is-invalid @enderror">
                        <option value="">Seçiniz</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('site_id', $apartment->site_id) == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                    @error('site_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Blok</label>
                    <select name="block_id" class="form-select @error('block_id') is-invalid @enderror">
                        <option value="">Seçiniz</option>
                        @foreach($blocks as $block)
                            <option value="{{ $block->id }}" {{ old('block_id', $apartment->block_id) == $block->id ? 'selected' : '' }}>{{ $block->name }}</option>
                        @endforeach
                    </select>
                    @error('block_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ad</label>
                    <input type="text" name="name" value="{{ old('name', $apartment->name) }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Adres</label>
                    <input type="text" name="address" value="{{ old('address', $apartment->address) }}" class="form-control @error('address') is-invalid @enderror">
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', $apartment->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $apartment->status) === 'inactive' ? 'selected' : '' }}>Pasif</option>
                        <option value="maintenance" {{ old('status', $apartment->status) === 'maintenance' ? 'selected' : '' }}>Bakımda</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">Toplam Kat</label>
                    <input type="number" name="total_floors" value="{{ old('total_floors', $apartment->total_floors) }}" class="form-control @error('total_floors') is-invalid @enderror">
                    @error('total_floors')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Toplam Daire</label>
                    <input type="number" name="total_flats" value="{{ old('total_flats', $apartment->total_flats) }}" class="form-control @error('total_flats') is-invalid @enderror">
                    @error('total_flats')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kat Başına Daire</label>
                    <input type="number" name="flats_per_floor" value="{{ old('flats_per_floor', $apartment->flats_per_floor) }}" class="form-control @error('flats_per_floor') is-invalid @enderror">
                    @error('flats_per_floor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">Asansör</label>
                    <select name="has_elevator" class="form-select @error('has_elevator') is-invalid @enderror">
                        <option value="0" {{ old('has_elevator', (int)$apartment->has_elevator) == 0 ? 'selected' : '' }}>Yok</option>
                        <option value="1" {{ old('has_elevator', (int)$apartment->has_elevator) == 1 ? 'selected' : '' }}>Var</option>
                    </select>
                    @error('has_elevator')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Otopark</label>
                    <select name="has_parking" class="form-select @error('has_parking') is-invalid @enderror">
                        <option value="0" {{ old('has_parking', (int)$apartment->has_parking) == 0 ? 'selected' : '' }}>Yok</option>
                        <option value="1" {{ old('has_parking', (int)$apartment->has_parking) == 1 ? 'selected' : '' }}>Var</option>
                    </select>
                    @error('has_parking')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


