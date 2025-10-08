@extends('layouts.panel')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0 d-flex align-items-center gap-2"><i class="bi bi-speedometer2"></i> Dashboard</h1>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Toplam Site</div>
                    <div class="h4 m-0" id="metric-total-sites">{{ number_format($totalSites ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Toplam Blok</div>
                    <div class="h4 m-0" id="metric-total-blocks">{{ number_format($totalBlocks ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Toplam Apartman</div>
                    <div class="h4 m-0" id="metric-total-apartments">{{ number_format($totalApartments ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Toplam Daire</div>
                    <div class="h4 m-0" id="metric-total-flats">{{ number_format($totalFlats ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-graph-up"></i> Genel Görünüm</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded h-100">
                                <div class="text-muted small mb-2">Daire Durum Dağılımı</div>
                                @php($total = array_sum([$flatStatusCounts['empty'] ?? 0, $flatStatusCounts['occupied'] ?? 0, $flatStatusCounts['maintenance'] ?? 0, $flatStatusCounts['renovation'] ?? 0]))
                                @php($pct = function($c,$t){ return $t>0 ? round(($c/$t)*100) : 0; })
                                <div class="small mb-2">Toplam: {{ number_format($total) }}</div>
                                <div class="small d-flex align-items-center justify-content-between">
                                    <span>Boş</span><span>{{ number_format(($flatStatusCounts['empty'] ?? 0)) }} ({{ $pct(($flatStatusCounts['empty'] ?? 0), $total) }}%)</span>
                                </div>
                                <div class="progress mb-2" role="progressbar" aria-label="Boş" aria-valuenow="{{ $pct(($flatStatusCounts['empty'] ?? 0), $total) }}" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-secondary" style="width: {{ $pct(($flatStatusCounts['empty'] ?? 0), $total) }}%"></div>
                                </div>
                                <div class="small d-flex align-items-center justify-content-between">
                                    <span>Dolu</span><span>{{ number_format(($flatStatusCounts['occupied'] ?? 0)) }} ({{ $pct(($flatStatusCounts['occupied'] ?? 0), $total) }}%)</span>
                                </div>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" style="width: {{ $pct(($flatStatusCounts['occupied'] ?? 0), $total) }}%"></div>
                                </div>
                                <div class="small d-flex align-items-center justify-content-between">
                                    <span>Bakım</span><span>{{ number_format(($flatStatusCounts['maintenance'] ?? 0)) }} ({{ $pct(($flatStatusCounts['maintenance'] ?? 0), $total) }}%)</span>
                                </div>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-warning" style="width: {{ $pct(($flatStatusCounts['maintenance'] ?? 0), $total) }}%"></div>
                                </div>
                                <div class="small d-flex align-items-center justify-content-between">
                                    <span>Tadilat</span><span>{{ number_format(($flatStatusCounts['renovation'] ?? 0)) }} ({{ $pct(($flatStatusCounts['renovation'] ?? 0), $total) }}%)</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: {{ $pct(($flatStatusCounts['renovation'] ?? 0), $total) }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded h-100 d-flex align-items-center justify-content-center text-muted small">
                                Grafik/heatmap placeholder
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-clock-history"></i> Son İşlemler</div>
                <div class="card-body">
                    <div class="small text-muted mb-2">Siteler</div>
                    <ul class="list-unstyled small mb-3">
                        @forelse($recentSites as $s)
                            <li class="mb-1"><a href="{{ route('sites.show', $s) }}" class="text-decoration-none">{{ $s->name }}</a> <span class="text-muted">({{ $s->site_code }})</span> • {{ $s->created_at?->format('d.m.Y') }}</li>
                        @empty
                            <li class="text-muted">Kayıt yok</li>
                        @endforelse
                    </ul>
                    <div class="small text-muted mb-2">Bloklar</div>
                    <ul class="list-unstyled small mb-3">
                        @forelse($recentBlocks as $b)
                            <li class="mb-1"><a href="{{ route('blocks.show', $b) }}" class="text-decoration-none">{{ $b->name }}</a> <span class="text-muted">— {{ $b->site?->name }}</span> • {{ $b->created_at?->format('d.m.Y') }}</li>
                        @empty
                            <li class="text-muted">Kayıt yok</li>
                        @endforelse
                    </ul>
                    <div class="small text-muted mb-2">Apartmanlar</div>
                    <ul class="list-unstyled small m-0">
                        @forelse($recentApartments as $a)
                            <li class="mb-1"><a href="{{ route('apartments.show', $a) }}" class="text-decoration-none">{{ $a->name }}</a> <span class="text-muted">— {{ $a->site?->name }} / {{ $a->block?->name }}</span> • {{ $a->created_at?->format('d.m.Y') }}</li>
                        @empty
                            <li class="text-muted">Kayıt yok</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-lightning-charge"></i> Hızlı İşlemler</div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <a href="{{ route('sites.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Yeni Site</a>
                    <a href="{{ route('blocks.create') }}" class="btn btn-outline-secondary"><i class="bi bi-plus-lg me-1"></i>Yeni Blok</a>
                    <a href="{{ route('apartments.create') }}" class="btn btn-outline-secondary"><i class="bi bi-plus-lg me-1"></i>Yeni Apartman</a>
                    <a href="{{ route('flats.index') }}" class="btn btn-outline-secondary"><i class="bi bi-search me-1"></i>Daire Ara</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
