@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Yeni Site</h1>
        <a href="{{ route('sites.index') }}" class="btn btn-light">Geri</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('sites.store') }}" method="POST" class="row g-3" data-confirm="Yeni site oluşturulsun mu?">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Ad</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kod</label>
                    <input type="text" name="site_code" value="{{ old('site_code') }}" class="form-control @error('site_code') is-invalid @enderror">
                    @error('site_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Adres</label>
                    <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" rows="2" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status')==='active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status')==='inactive' ? 'selected' : '' }}>Pasif</option>
                        <option value="maintenance" {{ old('status')==='maintenance' ? 'selected' : '' }}>Bakımda</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">Blok</label>
                    <input type="number" name="total_blocks" value="{{ old('total_blocks',0) }}" class="form-control @error('total_blocks') is-invalid @enderror">
                    @error('total_blocks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Apartman</label>
                    <input type="number" name="total_apartments" value="{{ old('total_apartments',0) }}" class="form-control @error('total_apartments') is-invalid @enderror">
                    @error('total_apartments')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Toplam Kat</label>
                    <input type="number" name="total_floors" value="{{ old('total_floors',0) }}" class="form-control @error('total_floors') is-invalid @enderror">
                    @error('total_floors')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kat Başına Daire</label>
                    <input type="number" name="flats_per_floor" value="{{ old('flats_per_floor',0) }}" class="form-control @error('flats_per_floor') is-invalid @enderror">
                    @error('flats_per_floor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


