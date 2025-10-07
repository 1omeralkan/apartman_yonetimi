@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 m-0">Sakin Ata – Daire {{ $flat->flat_number }}</h1>
        <a href="{{ route('settlement.apartment', $flat->apartment_id) }}" class="btn btn-light">Geri</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form id="assignForm" action="{{ route('settlement.assign', $flat) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Kullanıcı</label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">Seçiniz</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id')==$u->id ? 'selected' : '' }}>{{ $u->first_name }} {{ $u->last_name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tip</label>
                    <select name="resident_type" class="form-select @error('resident_type') is-invalid @enderror" required>
                        @foreach(['owner'=>'Ev Sahibi','tenant'=>'Kiracı','family_member'=>'Aile Üyesi','guest'=>'Misafir'] as $k=>$v)
                            <option value="{{ $k }}" {{ old('resident_type')==$k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('resident_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status','active')=='active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>Pasif</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kira (₺)</label>
                    <input type="number" name="rent_amount" step="0.01" min="0" value="{{ old('rent_amount') }}" class="form-control @error('rent_amount') is-invalid @enderror">
                    @error('rent_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Giriş Tarihi</label>
                    <input type="date" name="move_in_date" value="{{ old('move_in_date', now()->toDateString()) }}" class="form-control @error('move_in_date') is-invalid @enderror" required>
                    @error('move_in_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Çıkış Tarihi</label>
                    <input type="date" name="move_out_date" value="{{ old('move_out_date') }}" class="form-control @error('move_out_date') is-invalid @enderror">
                    @error('move_out_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Not</label>
                    <textarea name="notes" rows="2" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function(){
        const form = document.getElementById('assignForm');
        if(!form) return;
        form.addEventListener('submit', function(e){
            e.preventDefault();
            const userSelect = form.querySelector('select[name="user_id"]');
            const residentType = form.querySelector('select[name="resident_type"]');
            const status = form.querySelector('select[name="status"]');
            const moveIn = form.querySelector('input[name="move_in_date"]').value;
            const moveOut = form.querySelector('input[name="move_out_date"]').value;
            const rent = form.querySelector('input[name="rent_amount"]').value;

            const userText = userSelect.options[userSelect.selectedIndex]?.text || '';
            const typeText = residentType.options[residentType.selectedIndex]?.text || '';
            const statusText = status.options[status.selectedIndex]?.text || '';

            Swal.fire({
                title: 'Sakin atansın mı?',
                html: `
                    <div class="text-start small">
                        <div><strong>Kullanıcı:</strong> ${userText}</div>
                        <div><strong>Tip:</strong> ${typeText} • <strong>Durum:</strong> ${statusText}</div>
                        <div><strong>Giriş:</strong> ${moveIn || '-'} ${moveOut ? ' • <strong>Çıkış:</strong> '+moveOut : ''}</div>
                        ${rent ? `<div><strong>Kira:</strong> ₺${rent}</div>` : ''}
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Evet, Ata',
                cancelButtonText: 'Vazgeç',
                confirmButtonColor: '#2563eb'
            }).then((result)=>{
                if(result.isConfirmed){
                    form.submit();
                }
            });
        });
    })();
</script>
@endsection


