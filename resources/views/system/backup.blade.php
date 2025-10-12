@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Yedekleme Yönetimi</h1>
                    <p class="text-muted mb-0">Veritabanı ve dosya yedeklerini yönetin</p>
                </div>
                <a href="{{ route('system.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Geri Dön
                </a>
            </div>

            <!-- Yedekleme İşlemleri -->
            <div class="row mb-4">
                <div class="col-lg-4 mb-3">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="bi bi-database text-primary mb-3" style="font-size: 2rem;"></i>
                            <h5 class="card-title">Veritabanı Yedeği</h5>
                            <p class="card-text text-muted">Sadece veritabanı tablolarını yedekler</p>
                            <form method="POST" action="{{ route('system.backup.database') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-download me-2"></i>Veritabanı Yedeği Al
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="bi bi-folder text-success mb-3" style="font-size: 2rem;"></i>
                            <h5 class="card-title">Dosya Yedeği</h5>
                            <p class="card-text text-muted">Önemli dosyaları ve klasörleri yedekler</p>
                            <form method="POST" action="{{ route('system.backup.files') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-archive me-2"></i>Dosya Yedeği Al
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card border-warning">
                        <div class="card-body text-center">
                            <i class="bi bi-hdd text-warning mb-3" style="font-size: 2rem;"></i>
                            <h5 class="card-title">Tam Sistem Yedeği</h5>
                            <p class="card-text text-muted">Veritabanı + dosyaları birlikte yedekler</p>
                            <form method="POST" action="{{ route('system.backup.full') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-shield-check me-2"></i>Tam Yedek Al
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disk Kullanımı -->
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-hdd me-2"></i>Yedek Disk Kullanımı
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="text-muted small">Toplam Dosya</div>
                                    <div class="h5 mb-0">{{ $diskUsage['total_files'] }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">Toplam Boyut</div>
                                    <div class="h5 mb-0">{{ $diskUsage['total_size'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <form method="POST" action="{{ route('system.backup.delete-all') }}" 
                                  onsubmit="return confirm('Tüm yedek dosyalarını silmek istediğinizden emin misiniz?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-trash me-2"></i>Tüm Yedekleri Sil
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yedek Listesi -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-list-ul me-2"></i>Yedek Dosyaları
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($backups) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Dosya Adı</th>
                                        <th>Tür</th>
                                        <th>Boyut</th>
                                        <th>Oluşturulma Tarihi</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-file-zip text-primary me-2"></i>
                                                    <span class="fw-semibold">{{ $backup['name'] }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($backup['type'] == 'Veritabanı')
                                                    <span class="badge bg-primary">{{ $backup['type'] }}</span>
                                                @elseif($backup['type'] == 'Dosyalar')
                                                    <span class="badge bg-success">{{ $backup['type'] }}</span>
                                                @elseif($backup['type'] == 'Tam Sistem')
                                                    <span class="badge bg-warning">{{ $backup['type'] }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $backup['type'] }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $backup['size'] }}</td>
                                            <td>{{ $backup['created_at'] }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('system.backup.download', $backup['name']) }}" 
                                                       class="btn btn-outline-primary" title="İndir">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="deleteBackup('{{ $backup['name'] }}')" title="Sil">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-archive text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted">Yedek Dosyası Bulunamadı</h5>
                            <p class="text-muted">Henüz hiç yedek alınmamış. Yukarıdaki butonları kullanarak yedek alabilirsiniz.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Silme Onay Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yedek Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong id="backupName"></strong> adlı yedek dosyasını silmek istediğinizden emin misiniz?</p>
                <p class="text-danger"><small>Bu işlem geri alınamaz!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteBackup(backupName) {
    document.getElementById('backupName').textContent = backupName;
    document.getElementById('deleteForm').action = `/system/backup/${backupName}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
