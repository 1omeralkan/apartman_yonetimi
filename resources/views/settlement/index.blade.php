@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Yerleşim Yönetimi</h1>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Site Seç</label>
                    <select name="site_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Seçiniz</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ (string)$selectedSiteId === (string)$site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Göster</button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedSite)
        @php($count = $blocks->count())
        @php($cols = $count <= 2 ? 2 : ($count <= 4 ? 2 : ($count <= 6 ? 3 : 4)))

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $selectedSite->name }}</strong>
                    <span class="text-muted ms-2">Blok Sayısı: {{ $count }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($blocks as $block)
                        <div class="col-3">
                            <a href="{{ route('settlement.block', $block) }}" class="text-decoration-none text-reset d-block">
                                <div class="p-1 rounded border position-relative" style="background:#f6f8fb; border-color:#e2e8f0">
                                    <div class="ratio ratio-1x1 rounded" style="background:#e9eef6;">
                                        <div class="d-flex flex-column justify-content-center align-items-center h-100 px-1 text-center">
                                            <div class="fw-semibold" style="font-size:.95rem; line-height:1.15">{{ $block->name }}</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection


