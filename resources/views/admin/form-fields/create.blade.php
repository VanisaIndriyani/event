@extends('layouts.admin')

@section('title', 'Tambah Form Field')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title"><i class="fas fa-plus-circle me-3"></i>Tambah Form Field</h1>
                <p class="page-subtitle">Buat field baru untuk form registrasi event</p>
            </div>
            <div>
                <a href="{{ route('admin.form-fields.index') }}{{ request('event_id') ? '?event_id=' . request('event_id') : '' }}" class="btn btn-light btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="content-card">
        <form action="{{ route('admin.form-fields.store') }}" method="POST" id="form-field-form">
            @csrf
            
            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <div class="form-section">
                        <h5 class="section-title">Informasi Dasar</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="event_id" class="form-label required">Event</label>
                                <select name="event_id" id="event_id" class="form-select @error('event_id') is-invalid @enderror" required>
                                    <option value="">Pilih Event</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" {{ old('event_id', request('event_id')) == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('event_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="field_type" class="form-label required">Tipe Field</label>
                                <select name="field_type" id="field_type" class="form-select @error('field_type') is-invalid @enderror" required>
                                    <option value="">Pilih Tipe Field</option>
                                    @foreach($fieldTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('field_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('field_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="field_name" class="form-label required">Field Name</label>
                                <input type="text" name="field_name" id="field_name" class="form-control @error('field_name') is-invalid @enderror" value="{{ old('field_name') }}" placeholder="e.g., phone_number" required>
                                <div class="form-text">Nama field untuk database (gunakan snake_case)</div>
                                @error('field_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="field_label" class="form-label required">Field Label</label>
                                <input type="text" name="field_label" id="field_label" class="form-control @error('field_label') is-invalid @enderror" value="{{ old('field_label') }}" placeholder="e.g., Nomor Telepon" required>
                                <div class="form-text">Label yang ditampilkan di form</div>
                                @error('field_label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <label for="placeholder" class="form-label">Placeholder</label>
                            <input type="text" name="placeholder" id="placeholder" class="form-control @error('placeholder') is-invalid @enderror" value="{{ old('placeholder') }}" placeholder="Teks placeholder untuk field">
                            @error('placeholder')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mt-3">
                            <label for="help_text" class="form-label">Help Text</label>
                            <textarea name="help_text" id="help_text" class="form-control @error('help_text') is-invalid @enderror" rows="2" placeholder="Teks bantuan untuk field ini">{{ old('help_text') }}</textarea>
                            @error('help_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Options Section (for select, radio, checkbox) -->
                    <div class="form-section mt-4" id="options-section" style="display: none;">
                        <h5 class="section-title">Opsi Field</h5>
                        <div class="mb-3">
                            <label for="field_options" class="form-label">Opsi (satu per baris)</label>
                            <textarea name="field_options" id="field_options" class="form-control @error('field_options') is-invalid @enderror" rows="5" placeholder="Opsi 1&#10;Opsi 2&#10;Opsi 3">{{ old('field_options') }}</textarea>
                            <div class="form-text">Masukkan setiap opsi pada baris terpisah</div>
                            @error('field_options')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Validation Section -->
                    <div class="form-section mt-4">
                        <h5 class="section-title">Validasi</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="validation_rules" class="form-label">Aturan Validasi</label>
                                <input type="text" name="validation_rules" id="validation_rules" class="form-control @error('validation_rules') is-invalid @enderror" value="{{ old('validation_rules') }}" placeholder="e.g., min:3|max:50">
                                <div class="form-text">Aturan validasi Laravel (pisahkan dengan |)</div>
                                @error('validation_rules')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="field_order" class="form-label">Urutan</label>
                                <input type="number" name="field_order" id="field_order" class="form-control @error('field_order') is-invalid @enderror" value="{{ old('field_order', 1) }}" min="1">
                                <div class="form-text">Urutan tampil field di form</div>
                                @error('field_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="col-lg-4">
                    <div class="form-section">
                        <h5 class="section-title">Pengaturan</h5>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_required" id="is_required" value="1" {{ old('is_required') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_required">
                                Field Wajib Diisi
                            </label>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Field Aktif
                            </label>
                        </div>
                    </div>
                    
                    <!-- Preview Section -->
                    <div class="form-section mt-4">
                        <h5 class="section-title">Preview</h5>
                        <div id="field-preview" class="preview-container">
                            <p class="text-muted">Pilih tipe field untuk melihat preview</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan Form Field
                </button>
                <a href="{{ route('admin.form-fields.index') }}{{ request('event_id') ? '?event_id=' . request('event_id') : '' }}" class="btn btn-outline-secondary">
                    Batal
                </a>
            </div>
        </form>
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
    
    .form-section {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        color: #1f2937;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .required::after {
        content: ' *';
        color: #ef4444;
    }
    
    .preview-container {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        min-height: 100px;
    }
    
    .form-actions {
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
        background: #f8fafc;
        margin: -2rem -2rem 0 -2rem;
        padding: 1.5rem 2rem;
        border-radius: 0 0 12px 12px;
    }
    
    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .form-text {
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .form-label {
        color: #1f2937;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .form-control,
    .form-select {
        border: 1px solid #d1d5db;
        background: white;
        color: #1f2937;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }
    
    .form-check-label {
        color: #1f2937;
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
        border-color: #6b7280;
        color: #6b7280;
        background: white;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6b7280;
        color: white;
    }
    
    .text-muted {
        color: #6b7280 !important;
    }
    
    .text-danger {
        color: #ef4444 !important;
    }
    
    .is-invalid {
        border-color: #ef4444;
    }
    
    .invalid-feedback {
        color: #ef4444;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fieldTypeSelect = document.getElementById('field_type');
        const fieldNameInput = document.getElementById('field_name');
        const fieldLabelInput = document.getElementById('field_label');
        const placeholderInput = document.getElementById('placeholder');
        const optionsSection = document.getElementById('options-section');
        const previewContainer = document.getElementById('field-preview');
        const isRequiredCheck = document.getElementById('is_required');
        
        // Show/hide options section based on field type
        fieldTypeSelect.addEventListener('change', function() {
            const fieldType = this.value;
            const needsOptions = ['select', 'radio', 'checkbox'].includes(fieldType);
            
            optionsSection.style.display = needsOptions ? 'block' : 'none';
            updatePreview();
        });
        
        // Auto-generate field name from label
        fieldLabelInput.addEventListener('input', function() {
            if (!fieldNameInput.value || fieldNameInput.dataset.autoGenerated !== 'false') {
                const fieldName = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '_')
                    .replace(/^_+|_+$/g, '');
                fieldNameInput.value = fieldName;
                fieldNameInput.dataset.autoGenerated = 'true';
            }
        });
        
        fieldNameInput.addEventListener('input', function() {
            this.dataset.autoGenerated = 'false';
        });
        
        // Update preview when inputs change
        [fieldTypeSelect, fieldLabelInput, placeholderInput, isRequiredCheck].forEach(element => {
            element.addEventListener('change', updatePreview);
            element.addEventListener('input', updatePreview);
        });
        
        function updatePreview() {
            const fieldType = fieldTypeSelect.value;
            const fieldLabel = fieldLabelInput.value || 'Field Label';
            const placeholder = placeholderInput.value;
            const isRequired = isRequiredCheck.checked;
            
            if (!fieldType) {
                previewContainer.innerHTML = '<p class="text-muted">Pilih tipe field untuk melihat preview</p>';
                return;
            }
            
            let previewHtml = `<label class="form-label">${fieldLabel}${isRequired ? ' <span class="text-danger">*</span>' : ''}</label>`;
            
            switch (fieldType) {
                case 'text':
                    previewHtml += `<input type="text" class="form-control" placeholder="${placeholder}" disabled>`;
                    break;
                case 'number':
                    previewHtml += `<input type="number" class="form-control" placeholder="${placeholder}" disabled>`;
                    break;
                case 'file':
                    previewHtml += `<input type="file" class="form-control" accept="image/*" disabled>`;
                    break;
                default:
                    previewHtml += `<input type="text" class="form-control" placeholder="${placeholder}" disabled>`;
            }
            
            previewContainer.innerHTML = `<div class="form-group">${previewHtml}</div>`;
        }
        
        // Initial preview update
        updatePreview();
    });
</script>
@endpush