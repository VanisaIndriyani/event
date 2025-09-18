@extends('layouts.admin')

@section('title', 'Form Fields Management')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-0">
                    <i class="fas fa-list-alt me-3"></i>Form Fields Management
                </h1>
                <p class="page-subtitle mb-0">Manage registration form fields and their order</p>
            </div>
            <a href="{{ route('admin.form-fields.create') }}{{ $eventId ? '?event_id=' . $eventId : '' }}" class="btn btn-light btn-lg">
                <i class="fas fa-plus me-2"></i>Add New Field
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="content-card">
        <form method="GET" action="{{ route('admin.form-fields.index') }}" class="filter-form">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="event_id" class="form-label">Filter by Event</label>
                    <select name="event_id" id="event_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ $eventId == $event->id ? 'selected' : '' }}>
                                {{ $event->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label">Field Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="text" {{ request('type') == 'text' ? 'selected' : '' }}>Text</option>
                        <option value="number" {{ request('type') == 'number' ? 'selected' : '' }}>Number</option>
                        <option value="file" {{ request('type') == 'file' ? 'selected' : '' }}>File (Image)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="required" class="form-label">Required</label>
                    <select name="required" id="required" class="form-select">
                        <option value="">All Fields</option>
                        <option value="1" {{ request('required') == '1' ? 'selected' : '' }}>Required Only</option>
                        <option value="0" {{ request('required') == '0' ? 'selected' : '' }}>Optional Only</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="active" class="form-label">Status</label>
                    <select name="active" id="active" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.form-fields.index') }}{{ $eventId ? '?event_id=' . $eventId : '' }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Form Fields Table -->
    <div class="content-card">
        @if($formFields->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="sortable-table">
                    <thead>
                        <tr>
                            <th width="50">Order</th>
                            <th>Label</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Status</th>
                            <th>Event</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-tbody">
                        @foreach($formFields as $field)
                            <tr data-id="{{ $field->id }}">
                                <td>
                                    <i class="fas fa-grip-vertical drag-handle"></i>
                                    {{ $field->field_order }}
                                </td>
                                <td><strong>{{ $field->field_label }}</strong></td>
                                <td><code>{{ $field->field_name }}</code></td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($field->field_type) }}</span>
                                </td>
                                <td>
                                    @if($field->is_required)
                                        <span class="badge bg-danger">Required</span>
                                    @else
                                        <span class="badge bg-secondary">Optional</span>
                                    @endif
                                </td>
                                <td>
                                    @if($field->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $field->event->title }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.form-fields.show', $field) }}" 
                                           class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.form-fields.edit', $field) }}" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.form-fields.destroy', $field) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this form field?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $formFields->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-list-alt"></i>
                </div>
                <h3>No Form Fields Found</h3>
                <p>No form fields match your current filters. Try adjusting your search criteria or create a new form field.</p>
                <a href="{{ route('admin.form-fields.create') }}{{ $eventId ? '?event_id=' . $eventId : '' }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Create First Form Field
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Override admin layout for this page */
    .admin-content {
        background: #ffffff;
        min-height: 100vh;
        padding: 2rem;
    }
    
    .content-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
    }
    
    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
    }
    
    .content-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }
    
    .filter-form {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    .form-label {
        color: #1f2937;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .form-select {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        color: #1f2937;
    }
    
    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-outline-secondary {
        border: 1px solid #6b7280;
        color: #6b7280;
        background: white;
    }
    
    .btn-outline-secondary:hover {
        background: #6b7280;
        color: white;
    }
    
    .table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .table th {
        background: #1e3a8a;
        color: white;
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
    }
    
    .table td {
        border-color: #f3f4f6;
        vertical-align: middle;
        padding: 1rem 0.75rem;
        color: #1f2937;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .drag-handle {
        color: #6b7280;
        margin-right: 0.5rem;
        cursor: move;
    }
    
    .drag-handle:hover {
        color: #3b82f6;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.375rem 0.75rem;
    }
    
    .bg-info {
        background-color: #3b82f6 !important;
    }
    
    .bg-secondary {
        background-color: #6b7280 !important;
    }
    
    .bg-danger {
        background-color: #ef4444 !important;
    }
    
    .bg-success {
        background-color: #10b981 !important;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 4px;
    }
    
    .btn-outline-info {
        border-color: #3b82f6;
        color: #3b82f6;
    }
    
    .btn-outline-info:hover {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-outline-primary {
        border-color: #1e3a8a;
        color: #1e3a8a;
    }
    
    .btn-outline-primary:hover {
        background-color: #1e3a8a;
        color: white;
    }
    
    .btn-outline-danger {
        border-color: #ef4444;
        color: #ef4444;
    }
    
    .btn-outline-danger:hover {
        background-color: #ef4444;
        color: white;
    }
    
    code {
        background: #f1f5f9;
        color: #1e3a8a;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
        border: 1px solid #e2e8f0;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        color: #9ca3af;
    }
    
    .empty-state h3 {
        color: #1f2937;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .empty-state p {
        color: #6b7280;
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }
    
    /* Pagination styling */
    .pagination {
        --bs-pagination-color: #1e3a8a;
        --bs-pagination-bg: #ffffff;
        --bs-pagination-border-color: #e5e7eb;
        --bs-pagination-hover-color: #ffffff;
        --bs-pagination-hover-bg: #3b82f6;
        --bs-pagination-hover-border-color: #3b82f6;
        --bs-pagination-active-color: #ffffff;
        --bs-pagination-active-bg: #1e3a8a;
        --bs-pagination-active-border-color: #1e3a8a;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize sortable for drag and drop ordering
        const sortableElement = document.getElementById('sortable-tbody');
        if (sortableElement) {
            new Sortable(sortableElement, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function(evt) {
                    updateFieldOrder();
                }
            });
        }
        
        // Auto-submit filter form when filter values change
        const filterForm = document.querySelector('.filter-form');
        const filterSelects = filterForm.querySelectorAll('select:not(#event_id)');
        
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });
        
        function updateFieldOrder() {
            const rows = document.querySelectorAll('#sortable-tbody tr');
            const items = [];
            
            rows.forEach((row, index) => {
                items.push({
                    id: row.dataset.id,
                    order: index + 1
                });
            });
            
            fetch('{{ route("admin.form-fields.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ items: items })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update order numbers in the UI
                    rows.forEach((row, index) => {
                        const orderCell = row.querySelector('td:first-child');
                        const orderText = orderCell.childNodes[1]; // Text node after icon
                        if (orderText) {
                            orderText.textContent = ` ${index + 1}`;
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error updating order:', error);
                alert('Gagal mengupdate urutan field');
            });
        }
    });
</script>
@endpush