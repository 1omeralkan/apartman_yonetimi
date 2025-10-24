@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Başlık Bölümü -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="animate-fade-in">
                    <h1 class="h3 mb-0 text-gradient">⏳ Onay Bekleyen Kullanıcılar</h1>
                    <p class="text-muted mb-0">Admin onayı bekleyen kullanıcıları yönetin</p>
                </div>
                <div class="animate-slide-in-right">
                    <button type="button" class="btn btn-success btn-lg shadow-sm hover-lift me-2" id="bulkApproveBtn" disabled>
                        <i class="bi bi-check-all me-2"></i>Seçilenleri Onayla
                    </button>
                    <button type="button" class="btn btn-danger btn-lg shadow-sm hover-lift" id="bulkRejectBtn" disabled>
                        <i class="bi bi-x-circle me-2"></i>Seçilenleri Reddet
                    </button>
                </div>
            </div>

            <!-- İstatistik Kartları -->
            <div class="row mb-4 animate-fade-in-up">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center">
                            <div class="icon-wrapper bg-warning bg-gradient rounded-circle mx-auto mb-3">
                                <i class="bi bi-clock-history text-white"></i>
                            </div>
                            <h4 class="mb-1 text-warning">{{ $users->total() }}</h4>
                            <p class="text-muted mb-0">Onay Bekleyen</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center">
                            <div class="icon-wrapper bg-info bg-gradient rounded-circle mx-auto mb-3">
                                <i class="bi bi-calendar-week text-white"></i>
                            </div>
                            <h4 class="mb-1 text-info">{{ $users->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
                            <p class="text-muted mb-0">Son 7 Gün</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center">
                            <div class="icon-wrapper bg-success bg-gradient rounded-circle mx-auto mb-3">
                                <i class="bi bi-person-check text-white"></i>
                            </div>
                            <h4 class="mb-1 text-success">0</h4>
                            <p class="text-muted mb-0">Bugün Onaylanan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onay Bekleyen Kullanıcılar -->
            <div class="card border-0 shadow-sm animate-fade-in-up">
                <div class="card-header bg-white border-0 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>Onay Bekleyen Kullanıcı Listesi
                        </h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning me-2">{{ $users->total() }} Kullanıcı</span>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllPending">
                                <label class="form-check-label" for="selectAllPending">
                                    Tümünü Seç
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input me-2" id="selectAll">
                                                Kullanıcı
                                            </div>
                                        </th>
                                        <th class="border-0">Email</th>
                                        <th class="border-0">Telefon</th>
                                        <th class="border-0">Kayıt Tarihi</th>
                                        <th class="border-0">Roller</th>
                                        <th class="border-0 text-center">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $index => $user)
                                        <tr class="animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <input type="checkbox" class="form-check-input me-3 user-checkbox" value="{{ $user->id }}">
                                                    <div class="avatar-sm me-3">
                                                        <div class="avatar-title bg-gradient-warning text-white rounded-circle shadow-sm">
                                                            {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                                        <small class="text-muted">
                                                            <i class="bi bi-hash me-1"></i>ID: {{ $user->id }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $user->email }}</span>
                                                @if($user->email_verified_at)
                                                    <i class="bi bi-check-circle text-success ms-1" title="Email doğrulanmış"></i>
                                                @else
                                                    <i class="bi bi-exclamation-circle text-warning ms-1" title="Email doğrulanmamış"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->phone)
                                                    <span class="text-muted">{{ $user->phone }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    <i class="bi bi-calendar-plus me-1"></i>{{ $user->created_at->format('d.m.Y H:i') }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                @if($user->roles->count() > 0)
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-secondary me-1">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Rol atanmamış</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm shadow-sm" role="group">
                                                    <a href="{{ route('users.show', $user) }}" 
                                                       class="btn btn-outline-info hover-lift" 
                                                       title="Detayları Görüntüle">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-success hover-lift" 
                                                            onclick="approveUser({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')"
                                                            title="Kullanıcıyı Onayla">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger hover-lift" 
                                                            onclick="rejectUser({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')"
                                                            title="Kullanıcıyı Reddet">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Sayfalama -->
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div class="text-muted">
                                Toplam {{ $users->total() }} kullanıcıdan {{ $users->firstItem() }}-{{ $users->lastItem() }} arası gösteriliyor
                            </div>
                            <div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-success">Tüm kullanıcılar onaylandı!</h5>
                            <p class="text-muted">Şu anda onay bekleyen kullanıcı bulunmuyor.</p>
                            <a href="{{ route('users.index') }}" class="btn btn-primary">
                                <i class="bi bi-people me-2"></i>Tüm Kullanıcıları Görüntüle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toplu Onay Modal -->
<div class="modal fade" id="bulkApproveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Toplu Onaylama</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong id="selectedCount"></strong> kullanıcıyı onaylamak istediğinizden emin misiniz?</p>
                <p class="text-success"><small>Bu işlem geri alınamaz!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="bulkApproveForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Onayla</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tekil Onay Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kullanıcı Onayla</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong id="approveUserName"></strong> adlı kullanıcıyı onaylamak istediğinizden emin misiniz?</p>
                <p class="text-success"><small>Onaylandıktan sonra kullanıcı sisteme giriş yapabilecek.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="approveForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Onayla</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reddetme Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kullanıcı Onayını Reddet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Dikkat!</strong> Bu işlem kullanıcı kaydını tamamen silecektir.
                </div>
                <p><strong id="rejectUserName"></strong> adlı kullanıcının onayını reddetmek ve kaydını silmek istediğinizden emin misiniz?</p>
                <p class="text-danger"><small><strong>Bu işlem geri alınamaz!</strong> Kullanıcı veritabanından tamamen silinecektir.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="rejectForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Reddet ve Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Select All functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkApproveButton();
});

// Individual checkbox change
document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allCheckboxes = document.querySelectorAll('.user-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        const selectAllCheckbox = document.getElementById('selectAll');
        
        selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
        updateBulkButtons();
    });
});

// Update bulk buttons
function updateBulkButtons() {
    const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const bulkRejectBtn = document.getElementById('bulkRejectBtn');
    
    if (checkedCheckboxes.length > 0) {
        bulkApproveBtn.disabled = false;
        bulkRejectBtn.disabled = false;
        bulkApproveBtn.innerHTML = `<i class="bi bi-check-all me-2"></i>${checkedCheckboxes.length} Kullanıcıyı Onayla`;
        bulkRejectBtn.innerHTML = `<i class="bi bi-x-circle me-2"></i>${checkedCheckboxes.length} Kullanıcıyı Reddet`;
    } else {
        bulkApproveBtn.disabled = true;
        bulkRejectBtn.disabled = true;
        bulkApproveBtn.innerHTML = '<i class="bi bi-check-all me-2"></i>Seçilenleri Onayla';
        bulkRejectBtn.innerHTML = '<i class="bi bi-x-circle me-2"></i>Seçilenleri Reddet';
    }
}

// Bulk approve functionality
document.getElementById('bulkApproveBtn').addEventListener('click', function() {
    const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    const userIds = Array.from(checkedCheckboxes).map(cb => cb.value);
    
    if (userIds.length > 0) {
        document.getElementById('selectedCount').textContent = userIds.length;
        
        // Create form with user IDs
        const form = document.getElementById('bulkApproveForm');
        form.innerHTML = '@csrf';
        userIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'user_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        form.action = '{{ route("users.bulk-approve") }}';
        
        new bootstrap.Modal(document.getElementById('bulkApproveModal')).show();
    }
});

// Bulk reject functionality
document.getElementById('bulkRejectBtn').addEventListener('click', function() {
    const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    const userIds = Array.from(checkedCheckboxes).map(cb => cb.value);
    
    if (userIds.length > 0) {
        document.getElementById('selectedCount').textContent = userIds.length;
        
        // Create form with user IDs
        const form = document.getElementById('bulkApproveForm');
        form.innerHTML = '@csrf';
        userIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'user_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        form.action = '{{ route("users.bulk-reject") }}';
        
        new bootstrap.Modal(document.getElementById('bulkApproveModal')).show();
    }
});

// Approve single user
function approveUser(userId, userName) {
    document.getElementById('approveUserName').textContent = userName;
    document.getElementById('approveForm').action = `/users/${userId}/approve`;
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}

// Reject single user
function rejectUser(userId, userName) {
    document.getElementById('rejectUserName').textContent = userName;
    document.getElementById('rejectForm').action = `/users/${userId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endpush

@push('styles')
<style>
/* Animasyonlar */
.animate-fade-in {
    animation: fadeIn 0.6s ease-in-out;
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out;
}

.animate-slide-in-right {
    animation: slideInRight 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Hover efektleri */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Gradient text */
.text-gradient {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Icon wrapper */
.icon-wrapper {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* Avatar improvements */
.avatar-title {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Table improvements */
.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(255, 193, 7, 0.05);
    transform: scale(1.01);
}

/* Badge improvements */
.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.8em;
    border-radius: 0.5rem;
}

/* Button improvements */
.btn {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.5rem;
    border-bottom-left-radius: 0.5rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}

/* Card improvements */
.card {
    border-radius: 1rem;
    overflow: hidden;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table-responsive {
        border-radius: 0.5rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.5rem !important;
        margin-bottom: 0.25rem;
    }
}
</style>
@endpush
