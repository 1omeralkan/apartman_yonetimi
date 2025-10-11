@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <h1 class="h5 mb-3 d-flex align-items-center gap-2"><i class="bi bi-chat-dots"></i> Şikayetlerim</h1>
    <div class="card shadow-sm">
        <div class="card-body text-muted">
            Bu sayfa yakında şikayetlerinizi listelemenize ve yeni şikayet oluşturmanıza imkan verecek.
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('resident.home') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Geri</a>
    </div>
</div>
@endsection



