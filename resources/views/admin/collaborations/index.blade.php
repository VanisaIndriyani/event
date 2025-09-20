@extends('layouts.admin')

@section('title', 'Kelola Kolaborasi')

@push('styles')
<style>
    .collaborations-management {
        padding: 2rem 0;
        min-height: calc(100vh - 80px);
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        font-family: 'Orbitron', monospace;
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }
    
    .filters-section {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .filters-title {
        font-family: 'Orbitron', monospace;
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filters-title i {
        color: var(--accent-blue);
    }
    
    .form-control {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        color: #374151;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        background: rgba(255, 255, 255, 1);
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        color: #374151;
    }
    
    .form-control::placeholder {
        color: rgba(0, 0, 0, 0.5);
    }
    
    .btn-primary {
        background: var(--gradient-accent);
        border: none;
        border-radius: 10px;
        color: white;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        color: white;
    }
    
    .collaborations-table {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
    }
    
    .table {
        margin: 0;
        color: #374151;
    }
    
    .table thead th {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border: none;
        color: white;
        font-family: 'Orbitron', monospace;
        font-weight: 600;
        padding: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.875rem;
    }
    
    .table tbody td {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(0, 0, 0, 0.1);
        padding: 1rem;
        vertical-align: middle;
    }
    
    .table tbody tr:hover {
        background: rgba(59, 130, 246, 0.1);
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .status-approved {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .status-rejected {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .btn-info {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        border: none;
        color: white;
    }
    
    .btn-info:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(6, 182, 212, 0.3);
        color: white;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        color: white;
    }
    
    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
        color: white;
    }
    
    .pagination {
        margin: 0;
    }
    
    .page-link {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        background: var(--accent-blue);
        border-color: var(--accent-blue);
        color: white;
        transform: translateY(-1px);
    }
    
    .page-item.active .page-link {
        background: var(--gradient-accent);
        border-color: var(--accent-blue);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #9ca3af;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        font-family: 'Orbitron', monospace;
        font-size: 1.5rem;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        font-size: 0.875rem;
        opacity: 0.7;
        color: #6b7280;
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .table-responsive {
            border-radius: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="collaborations-management">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Collaborations Management</h1>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <h3 class="filters-title">
                <i class="fas fa-filter"></i>
                Filter & Search
            </h3>
            <form method="GET" action="{{ route('admin.collaborations.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari nama, email, atau organisasi..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewing" {{ request('status') == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="collaboration_type" class="form-control">
                            <option value="">Semua Tipe</option>
                            <option value="corporate" {{ request('collaboration_type') == 'corporate' ? 'selected' : '' }}>Corporate Events</option>
                            <option value="brand" {{ request('collaboration_type') == 'brand' ? 'selected' : '' }}>Brand Partnership</option>
                            <option value="community" {{ request('collaboration_type') == 'community' ? 'selected' : '' }}>Community Events</option>
                            <option value="custom" {{ request('collaboration_type') == 'custom' ? 'selected' : '' }}>Custom Experience</option>
                            <option value="sponsorship" {{ request('collaboration_type') == 'sponsorship' ? 'selected' : '' }}>Sponsorship</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Collaborations Table -->
        <div class="collaborations-table">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Organisasi</th>
                            <th>Email</th>
                            <th>Tipe Kolaborasi</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($collaborations as $index => $collaboration)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $collaboration->name }}</td>
                                <td>{{ $collaboration->organization ?? '-' }}</td>
                                <td>{{ $collaboration->email }}</td>
                                <td>
                                    @switch($collaboration->collaboration_type)
                                        @case('corporate')
                                            Corporate Events
                                            @break
                                        @case('brand')
                                            Brand Partnership
                                            @break
                                        @case('community')
                                            Community Events
                                            @break
                                        @case('custom')
                                            Custom Experience
                                            @break
                                        @case('sponsorship')
                                            Sponsorship
                                            @break
                                        @default
                                            {{ ucfirst($collaboration->collaboration_type) }}
                                    @endswitch
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($collaboration->status) {
                                            'pending' => 'status-pending',
                                            'approved' => 'status-approved',
                                            'rejected' => 'status-rejected',
                                            default => 'status-pending'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        <i class="fas fa-circle"></i>
                                        {{ ucfirst($collaboration->status) }}
                                    </span>
                                </td>
                                <td>{{ $collaboration->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.collaborations.show', $collaboration->id) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                       
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-handshake"></i>
                                        <h3>Tidak ada data kolaborasi</h3>
                                        <p>Belum ada pengajuan kolaborasi yang masuk</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($collaborations->hasPages())
                <div class="d-flex justify-content-center p-3">
                    {{ $collaborations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--glass-bg); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-light);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                <h5 class="modal-title" style="color: var(--text-light);">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data kolaborasi ini?
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteCollaboration(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/collaborations/${id}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('select[name="status"]');
    const typeSelect = document.querySelector('select[name="collaboration_type"]');
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>
@endpush