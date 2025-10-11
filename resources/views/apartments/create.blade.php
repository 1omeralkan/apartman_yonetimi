@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Yeni Apartman</h1>
        <a href="{{ route('apartments.index') }}" class="btn btn-light">Geri</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('apartments.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Site</label>
                    <select name="site_id" class="form-select @error('site_id') is-invalid @enderror" onchange="window.fillBlocksForSite(this.value)">
                        <option value="">Seçiniz</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ (string)old('site_id', $selectedSiteId ?? '') === (string)$site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                    @error('site_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Blok</label>
                    <select name="block_id" class="form-select @error('block_id') is-invalid @enderror" id="block-select">
                        <option value="">Önce site seçiniz</option>
                        @foreach($blocks as $block)
                            <option value="{{ $block->id }}" {{ (string)old('block_id') === (string)$block->id ? 'selected' : '' }}>{{ $block->name }}</option>
                        @endforeach
                    </select>
                    @error('block_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ad</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Adres</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror">
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                    <label class="form-label">Kat Başına Daire</label>
                    <input type="number" name="flats_per_floor" value="{{ old('flats_per_floor',0) }}" class="form-control @error('flats_per_floor') is-invalid @enderror">
                    @error('flats_per_floor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Varsayılan Daire Tipi</label>
                    <select name="flat_type" class="form-select">
                        @foreach(($flatTypes ?? ['1+0','1+1','2+1','3+1','4+1','5+1']) as $type)
                            <option value="{{ $type }}" {{ old('flat_type','2+1') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Otomatik daire üretimi için varsayılan tip.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Varsayılan Aylık Aidat</label>
                    <div class="input-group">
                        <span class="input-group-text">₺</span>
                        <input type="number" step="0.01" min="0" name="monthly_dues" value="{{ old('monthly_dues') }}" class="form-control">
                    </div>
                    <div class="form-text">Otomatik oluşturulacak dairelere başlangıç aidatı olarak uygulanır (opsiyonel).</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Balkon</label>
                    <select name="has_balcony" class="form-select">
                        <option value="0" {{ old('has_balcony','0')==='0' ? 'selected' : '' }}>Yok</option>
                        <option value="1" {{ old('has_balcony')==='1' ? 'selected' : '' }}>Var</option>
                    </select>
                    <div class="form-text">Otomatik oluşturulan daireler için varsayılan balkon bilgisi.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Varsayılan Brüt Alan (m²)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" name="gross_area" value="{{ old('gross_area') }}" class="form-control">
                        <span class="input-group-text">m²</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Varsayılan Net Alan (m²)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" name="net_area" value="{{ old('net_area') }}" class="form-control">
                        <span class="input-group-text">m²</span>
                    </div>
                </div>

                

                <div class="col-md-2">
                    <label class="form-label">Asansör</label>
                    <select name="has_elevator" class="form-select @error('has_elevator') is-invalid @enderror">
                        <option value="0" {{ old('has_elevator','0')==='0' ? 'selected' : '' }}>Yok</option>
                        <option value="1" {{ old('has_elevator')==='1' ? 'selected' : '' }}>Var</option>
                    </select>
                    @error('has_elevator')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Otopark</label>
                    <select name="has_parking" class="form-select @error('has_parking') is-invalid @enderror">
                        <option value="0" {{ old('has_parking','0')==='0' ? 'selected' : '' }}>Yok</option>
                        <option value="1" {{ old('has_parking')==='1' ? 'selected' : '' }}>Var</option>
                    </select>
                    @error('has_parking')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
window.fillBlocksForSite = async function(siteId){
    const select = document.getElementById('block-select');
    select.innerHTML = '<option value="">Yükleniyor...</option>';
    if(!siteId){
        select.innerHTML = '<option value="">Önce site seçiniz</option>';
        return;
    }
    try{
        const res = await fetch('{{ url('api/sites') }}/' + siteId + '/blocks', {headers:{'X-Requested-With':'XMLHttpRequest'}});
        const data = await res.json();
        let options = '<option value="">Seçiniz</option>';
        data.forEach(b=>{ options += `<option value="${b.id}">${b.name}</option>`; });
        select.innerHTML = options;
    }catch(e){
        select.innerHTML = '<option value="">Bloklar yüklenemedi</option>';
    }
}
</script>
@endpush
