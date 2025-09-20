@extends('layouts.admin')

@section('title', 'Portfolio Management')

@section('content')
<div class="portfolio-management">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-images"></i>
            Portfolio Management
        </h1>
        <p class="page-description">Kelola portfolio dari event yang telah berakhir</p>
        <div class="page-actions">
            <a href="{{ route('admin.portfolios.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Tambah Portfolio
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="portfolio-filters">
        <div class="filter-group">
            <label for="status-filter">Status:</label>
            <select id="status-filter" class="form-control">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="search">Cari:</label>
            <input type="text" id="search" class="form-control" placeholder="Cari portfolio..." value="{{ request('search') }}">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-futuristic">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Event</th>
                    <th>Status</th>
                    <th>Jumlah Gambar</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($portfolios as $index => $portfolio)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($portfolio->first_image)
                                <img src="{{ Storage::disk('public_uploads')->url($portfolio->first_image) }}" alt="{{ $portfolio->title }}" class="portfolio-thumbnail">
                            @else
                                <div class="no-image">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="portfolio-title">
                                <strong>{{ $portfolio->title }}</strong>
                                @if($portfolio->description)
                                    <small class="text-muted d-block">{{ Str::limit($portfolio->description, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($portfolio->event)
                                <span class="event-name">{{ $portfolio->event->title }}</span>
                                <small class="text-muted d-block">{{ $portfolio->event->event_date->format('d M Y') }}</small>
                            @else
                                <span class="text-muted">Event tidak ditemukan</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $portfolio->status == 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($portfolio->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="image-count">
                                <i class="fas fa-images"></i>
                                {{ $portfolio->image_count }}
                            </span>
                        </td>
                        <td>{{ $portfolio->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.portfolios.show', $portfolio) }}" class="btn btn-sm btn-info" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.portfolios.edit', $portfolio) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.portfolios.destroy', $portfolio) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus portfolio ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="empty-state">
                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada portfolio</h5>
                                <p class="text-muted">Portfolio akan otomatis dibuat ketika event berakhir, atau Anda dapat membuat manual.</p>
                                <a href="{{ route('admin.portfolios.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Tambah Portfolio Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($portfolios->hasPages())
        <div class="pagination-wrapper">
            {{ $portfolios->links() }}
        </div>
    @endif
</div>

<style>
.portfolio-management {
    padding: 20px;
}

.portfolio-filters {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    backdrop-filter: blur(10px);
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-group label {
    font-weight: 600;
    color: #fff;
    font-size: 0.9rem;
}

.filter-group .form-control {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    color: #ffffff !important;
    padding: 8px 12px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.filter-group .form-control:focus {
    outline: none;
    border-color: #4fc3f7;
    box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.1);
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff !important;
}

.filter-group .form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.filter-group .form-control::-webkit-input-placeholder {
    color: rgba(255, 255, 255, 0.6) !important;
}

.filter-group .form-control::-moz-placeholder {
    color: rgba(255, 255, 255, 0.6) !important;
}

.filter-group .form-control:-ms-input-placeholder {
    color: rgba(255, 255, 255, 0.6) !important;
}

.filter-group select.form-control {
    cursor: pointer;
}

.filter-group select.form-control option {
    background: #1a1a1a;
    color: #fff;
}

.portfolio-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid rgba(255, 255, 255, 0.1);
}

.no-image {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255, 255, 255, 0.5);
}

.portfolio-title strong {
    color: #fff;
}

.event-name {
    color: #4fc3f7;
    font-weight: 500;
}

.image-count {
    color: #81c784;
    font-weight: 500;
}

.badge-success {
    background: linear-gradient(135deg, #4caf50, #45a049);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
}

.badge-warning {
    background: linear-gradient(135deg, #ff9800, #f57c00);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.action-buttons .btn {
    padding: 5px 8px;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.table-responsive {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

@media (max-width: 768px) {
    .portfolio-filters {
        flex-direction: column;
        gap: 15px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .portfolio-thumbnail {
        width: 40px;
        height: 40px;
    }
    
    .no-image {
        width: 40px;
        height: 40px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status-filter');
    const searchInput = document.getElementById('search');
    
    function updateFilters() {
        const url = new URL(window.location);
        const status = statusFilter.value;
        const search = searchInput.value;
        
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        
        window.location = url;
    }
    
    statusFilter.addEventListener('change', updateFilters);
    
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(updateFilters, 500);
    });
});
</script>
@endsection