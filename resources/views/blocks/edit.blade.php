@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Blok Düzenle</h1>
        <a href="{{ route('blocks.index') }}" class="btn btn-light">Geri</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('blocks.update', $block) }}" method="POST" class="row g-3" data-confirm="Blok bilgileri güncellensin mi?">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Site</label>
                    <select name="site_id" class="form-select @error('site_id') is-invalid @enderror">
                        <option value="">Seçiniz</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('site_id', $block->site_id) == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                    @error('site_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ad</label>
                    <input type="text" name="name" value="{{ old('name', $block->name) }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', $block->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $block->status) === 'inactive' ? 'selected' : '' }}>Pasif</option>
                        <option value="maintenance" {{ old('status', $block->status) === 'maintenance' ? 'selected' : '' }}>Bakımda</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                
                <div class="col-md-2">
                    <label class="form-label">Kat Başına Daire</label>
                    <input type="number" name="flats_per_floor" value="{{ old('flats_per_floor', $block->flats_per_floor) }}" class="form-control @error('flats_per_floor') is-invalid @enderror">
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


