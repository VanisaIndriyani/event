@extends('layouts.admin')

@section('title', 'Merchandise Management')

@push('styles')
<style>
    .merchandise-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    
    .merchandise-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 123, 255, 0.3);
    }
    
    .table-futuristic {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        overflow: hidden;
    }
    
    .table-futuristic th {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        border: none;
        padding: 15px;
        font-family: 'Orbitron', monospace;
    }
    
    .table-futuristic td {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 12px 15px;
        color: #e0e6ed;
    }
    
    .table-futuristic tbody tr:hover {
        background: rgba(0, 123, 255, 0.1);
    }
    
    .merchandise-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .no-image {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #374151, #4b5563);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 0.7rem;
        color: #9ca3af;
    }
    
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-active {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
    }
    
    .btn-action {
        padding: 8px 12px;
        border-radius: 8px;
        border: none;
        font-size: 0.85rem;
        margin: 0 2px;
        transition: all 0.3s ease;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #000;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    .btn-add {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
        padding: 12px 24px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        color: #fff;
    }
    
    /* Table Responsive - Bootstrap Style */
     .table-responsive {
         border-radius: 15px;
         box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
     }
     

    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .table-futuristic th,
        .table-futuristic td {
            padding: 8px 10px;
            font-size: 0.85rem;
        }
        
        .merchandise-thumb,
        .no-image {
            width: 40px;
            height: 40px;
        }
        
        .btn-action {
            padding: 6px 8px;
            font-size: 0.75rem;
            margin: 0 1px;
        }
        
        .status-badge {
            padding: 3px 8px;
            font-size: 0.7rem;
        }
        
        .table-futuristic th {
            white-space: nowrap;
        }
    }
    
    @media (max-width: 480px) {
        .table-futuristic th,
        .table-futuristic td {
            padding: 6px 8px;
            font-size: 0.8rem;
        }
        
        .merchandise-thumb,
        .no-image {
            width: 35px;
            height: 35px;
        }
        
        .btn-action {
            padding: 4px 6px;
            font-size: 0.7rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: rgba(40, 167, 69, 0.2); border: 1px solid rgba(40, 167, 69, 0.3); color: #28a745; border-radius: 10px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; border-radius: 10px;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-white mb-1" style="font-family: 'Orbitron', monospace;">Merchandise Management</h2>
            <p class="text-white mb-0">Manage your merchandise items and inventory</p>
        </div>
        <div>
            <a href="{{ route('admin.merchandise.create') }}" class="btn btn-add">
                <i class="fas fa-plus me-2"></i>Add New Merchandise
            </a>
        </div>
    </div>

    <!-- Merchandise List -->
    <div class="table-futuristic">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($merchandise) && $merchandise->count() > 0)
                    @foreach($merchandise as $item)
                    <tr>
                        <td>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="merchandise-thumb">
                            @else
                                <div class="no-image">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $item->name }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $item->stock > 0 ? 'bg-info' : 'bg-warning' }}">
                                {{ $item->stock }} pcs
                            </span>
                        </td>
                        <td>{{ $item->category ?? 'General' }}</td>
                        <td>
                            <span class="status-badge {{ $item->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.merchandise.edit', $item->id) }}" class="btn btn-action btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.merchandise.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-box-open" style="font-size: 4rem; color: rgba(255, 255, 255, 0.3);"></i>
                            </div>
                            <h5 class="text-white mb-3">No merchandise found</h5>
                            <p class="text-muted mb-4">Start by adding your first merchandise item to get started.</p>
                            <a href="{{ route('admin.merchandise.create') }}" class="btn btn-add">
                                <i class="fas fa-plus me-2"></i>Add First Merchandise
                            </a>
                        </td>
                    </tr>
                @endif
            </tbody>
            </table>
        </div>
    </div>
    
    @if(isset($merchandise) && method_exists($merchandise, 'links'))
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Merchandise pagination">
                <div class="pagination-wrapper" style="background: rgba(255, 255, 255, 0.1); border-radius: 10px; padding: 15px;">
                    {{ $merchandise->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </nav>
        </div>
    @endif
</div>



@endsection