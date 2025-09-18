@extends('layouts.admin')

@section('title', 'Form Fields - ' . $event->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Form Fields - {{ $event->title }}</h4>
                        <small class="text-muted">Kelola field form pendaftaran untuk event ini</small>
                    </div>
                    <div>
                        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('admin.events.form-fields.create', $event) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Field
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($formFields->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped" id="sortable-table">
                                <thead>
                                    <tr>
                                        <th width="50"><i class="fas fa-grip-vertical"></i></th>
                                        <th>Label</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Status</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-body">
                                    @foreach($formFields as $field)
                                    <tr data-id="{{ $field->id }}">
                                        <td class="handle" style="cursor: move;">
                                            <i class="fas fa-grip-vertical text-muted"></i>
                                        </td>
                                        <td>{{ $field->field_label }}</td>
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
                                                <span class="badge bg-warning">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.events.form-fields.edit', [$event, $field]) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.events.form-fields.destroy', [$event, $field]) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Yakin ingin menghapus field ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
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
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada form fields</h5>
                            <p class="text-muted">Tambahkan field form untuk mengumpulkan data pendaftaran yang diperlukan.</p>
                            <a href="{{ route('admin.events.form-fields.create', $event) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Field Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortableBody = document.getElementById('sortable-body');
    if (sortableBody) {
        new Sortable(sortableBody, {
            handle: '.handle',
            animation: 150,
            onEnd: function(evt) {
                const orders = [];
                const rows = sortableBody.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    orders.push({
                        id: row.dataset.id,
                        position: index + 1
                    });
                });
                
                fetch(`{{ route('admin.events.form-fields.update-order', $event) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ orders: orders })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        throw new Error('Response is not JSON');
                    }
                })
                .then(data => {
                    if (data.success) {
                        // Optional: Show success message
                        console.log('Order updated successfully');
                    }
                })
                .catch(error => {
                    console.error('Error updating order:', error);
                });
            }
        });
    }
});
</script>
@endpush