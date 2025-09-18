@extends('layouts.admin')

@section('title', 'Detail Form Field')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-0">
                    <i class="fas fa-eye me-3"></i>Detail Form Field
                </h1>
                <p class="page-subtitle mb-0">{{ $formField->field_label }} - {{ $formField->event->title }}</p>
            </div>
            <div>
                <a href="{{ route('admin.form-fields.edit', $formField) }}" class="btn btn-light me-2">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                <a href="{{ route('admin.form-fields.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <div class="content-card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>Informasi Field</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Event</label>
                                <div class="info-value">
                                    <span class="badge bg-info">{{ $formField->event->title }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Tipe Field</label>
                                <div class="info-value">
                                    <span class="badge bg-secondary">{{ ucfirst($formField->field_type) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Field Name</label>
                                <div class="info-value">
                                    <code>{{ $formField->field_name }}</code>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Field Label</label>
                                <div class="info-value">{{ $formField->field_label }}</div>
                            </div>
                        </div>
                        
                        @if($formField->placeholder)
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Placeholder</label>
                                <div class="info-value text-muted">{{ $formField->placeholder }}</div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Urutan</label>
                                <div class="info-value">{{ $formField->field_order }}</div>
                            </div>
                        </div>
                        
                        @if($formField->help_text)
                        <div class="col-12">
                            <div class="info-group">
                                <label class="info-label">Help Text</label>
                                <div class="info-value">{{ $formField->help_text }}</div>
                            </div>
                        </div>
                        @endif
                        
                        @if($formField->validation_rules)
                        <div class="col-12">
                            <div class="info-group">
                                <label class="info-label">Aturan Validasi</label>
                                <div class="info-value">
                                    <code>{{ $formField->validation_rules }}</code>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($formField->field_options && in_array($formField->field_type, ['select', 'radio', 'checkbox']))
            <div class="content-card mt-4">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-list me-2"></i>Opsi Field</h5>
                </div>
                <div class="card-body">
                    <div class="options-list">
                        @foreach($formField->field_options as $index => $option)
                            <div class="option-item">
                                <span class="option-number">{{ $index + 1 }}</span>
                                <span class="option-text">{{ $option }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Preview Section -->
            <div class="content-card mt-4">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-eye me-2"></i>Preview Field</h5>
                </div>
                <div class="card-body">
                    <div class="field-preview">
                        <label class="form-label">
                            {{ $formField->field_label }}
                            @if($formField->is_required)
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        
                        @switch($formField->field_type)
                            @case('text')
                            @case('email')
                            @case('number')
                            @case('phone')
                                <input type="{{ $formField->field_type === 'phone' ? 'tel' : $formField->field_type }}" 
                                       class="form-control" 
                                       placeholder="{{ $formField->placeholder }}" 
                                       disabled>
                                @break
                                
                            @case('textarea')
                                <textarea class="form-control" 
                                          placeholder="{{ $formField->placeholder }}" 
                                          rows="3" 
                                          disabled></textarea>
                                @break
                                
                            @case('select')
                                <select class="form-select" disabled>
                                    <option>Pilih opsi...</option>
                                    @if($formField->field_options)
                                        @foreach($formField->field_options as $option)
                                            <option>{{ $option }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @break
                                
                            @case('radio')
                                @if($formField->field_options)
                                    @foreach($formField->field_options as $index => $option)
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="preview_radio" 
                                                   id="preview_radio_{{ $index }}" 
                                                   disabled>
                                            <label class="form-check-label" for="preview_radio_{{ $index }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                                @break
                                
                            @case('checkbox')
                                @if($formField->field_options)
                                    @foreach($formField->field_options as $index => $option)
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="preview_checkbox_{{ $index }}" 
                                                   disabled>
                                            <label class="form-check-label" for="preview_checkbox_{{ $index }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                                @break
                                
                            @case('date')
                                <input type="date" class="form-control" disabled>
                                @break
                        @endswitch
                        
                        @if($formField->help_text)
                            <div class="form-text mt-2">{{ $formField->help_text }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="content-card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>Status</h5>
                </div>
                <div class="card-body">
                    <div class="status-item">
                        <span class="status-label">Required</span>
                        <span class="status-value">
                            @if($formField->is_required)
                                <span class="badge bg-danger">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Active</span>
                        <span class="status-value">
                            @if($formField->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </span>
                    </div>
                    @if($formField->validation_rules)
                    <div class="status-item">
                        <span class="status-label">Validation</span>
                        <span class="status-value">
                            <code>{{ $formField->validation_rules }}</code>
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Metadata Card -->
            <div class="content-card mt-4">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-clock me-2"></i>Metadata</h5>
                </div>
                <div class="card-body">
                    <div class="metadata-item">
                        <span class="metadata-label">Created</span>
                        <span class="metadata-value">
                            <small class="text-muted">{{ $formField->created_at->format('M d, Y H:i') }}</small>
                        </span>
                    </div>
                    <div class="metadata-item">
                        <span class="metadata-label">Updated</span>
                        <span class="metadata-value">
                            <small class="text-muted">{{ $formField->updated_at->format('M d, Y H:i') }}</small>
                        </span>
                    </div>
                    @if($formField->description)
                    <div class="metadata-item">
                        <span class="metadata-label">Description</span>
                        <span class="metadata-value">
                            <small>{{ $formField->description }}</small>
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Actions Card -->
            <div class="content-card mt-4">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-cogs me-2"></i>Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.form-fields.edit', $formField) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Field
                        </a>
                        <a href="{{ route('admin.form-fields.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                        <form action="{{ route('admin.form-fields.destroy', $formField) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this field?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Field
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
        padding: 0;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    
    .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
    }
    
    .card-title {
        color: #1f2937;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }
    
    .card-body {
        padding: 1.5rem;
        background: white;
    }
    
    .info-group {
        margin-bottom: 1rem;
    }
    
    .info-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        color: #1f2937;
        font-size: 1rem;
    }
    
    .status-item,
    .metadata-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .status-item:last-child,
    .metadata-item:last-child {
        border-bottom: none;
    }
    
    .status-label,
    .metadata-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .status-value,
    .metadata-value {
        text-align: right;
        color: #1f2937;
    }
    
    .options-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .option-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }
    
    .option-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: #3b82f6;
        color: white;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: 600;
        margin-right: 0.75rem;
    }
    
    .option-text {
        color: #1f2937;
    }
    
    .field-preview {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    .field-preview .form-label {
        color: #1f2937;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .field-preview .form-control,
    .field-preview .form-select {
        border: 1px solid #d1d5db;
        background: white;
        color: #1f2937;
    }
    
    .field-preview .form-check-label {
        color: #1f2937;
    }
    
    code {
        background: #f1f5f9;
        color: #1e3a8a;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
        border: 1px solid #e2e8f0;
    }
    
    .form-text {
        color: #6b7280;
        font-size: 0.875rem;
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
    
    .btn-outline-primary {
        border-color: #1e3a8a;
        color: #1e3a8a;
        background: white;
    }
    
    .btn-outline-primary:hover {
        background-color: #1e3a8a;
        color: white;
    }
    
    .btn-outline-danger {
        border-color: #ef4444;
        color: #ef4444;
        background: white;
    }
    
    .btn-outline-danger:hover {
        background-color: #ef4444;
        color: white;
    }
    
    .text-muted {
        color: #6b7280 !important;
    }
    
    .text-danger {
        color: #ef4444 !important;
    }
</style>
@endpush