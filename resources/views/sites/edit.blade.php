@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Siteyi Düzenle</h1>
        <a href="{{ route('sites.index') }}" class="btn btn-light">Geri</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('sites.update', $site) }}" method="POST" class="row g-3" data-confirm="Site bilgileri güncellensin mi?">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Ad</label>
                    <input type="text" name="name" value="{{ old('name', $site->name) }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kod</label>
                    <input type="text" name="site_code" value="{{ old('site_code', $site->site_code) }}" class="form-control @error('site_code') is-invalid @enderror">
                    @error('site_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Adres</label>
                    <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address', $site->address) }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" rows="2" class="form-control @error('description') is-invalid @enderror">{{ old('description', $site->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        @php $v = old('status', $site->status); @endphp
                        <option value="active" {{ $v==='active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $v==='inactive' ? 'selected' : '' }}>Pasif</option>
                        <option value="maintenance" {{ $v==='maintenance' ? 'selected' : '' }}>Bakımda</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">Blok</label>
                    <input type="number" name="total_blocks" value="{{ old('total_blocks', $site->total_blocks) }}" class="form-control @error('total_blocks') is-invalid @enderror">
                    @error('total_blocks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Blok Başına Apartman</label>
                    <input type="number" name="apartments_per_block" value="{{ old('apartments_per_block', $site->apartments_per_block) }}" class="form-control @error('apartments_per_block') is-invalid @enderror">
                    @error('apartments_per_block')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Apartman Başına Kat</label>
                    <input type="number" name="floors_per_apartment" value="{{ old('floors_per_apartment', $site->floors_per_apartment) }}" class="form-control @error('floors_per_apartment') is-invalid @enderror">
                    @error('floors_per_apartment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kat Başına Daire</label>
                    <input type="number" name="flats_per_floor" value="{{ old('flats_per_floor', $site->flats_per_floor) }}" class="form-control @error('flats_per_floor') is-invalid @enderror">
                    @error('flats_per_floor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


