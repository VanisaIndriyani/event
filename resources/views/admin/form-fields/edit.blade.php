@extends('layouts.admin')

@section('title', 'Edit Form Field')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div class="header-left">
            <h1 class="page-title">Edit Form Field</h1>
            <p class="page-subtitle">Edit field untuk form registrasi event</p>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.form-fields.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="content-card">
        <form action="{{ route('admin.form-fields.update', $formField) }}" method="POST" id="form-field-form">
            @csrf
            @method('PUT')
            
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
                                        <option value="{{ $event->id }}" {{ old('event_id', $formField->event_id) == $event->id ? 'selected' : '' }}>
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
                                        <option value="{{ $value }}" {{ old('field_type', $formField->field_type) == $value ? 'selected' : '' }}>{{ $label }}</option>
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
                                <input type="text" name="field_name" id="field_name" class="form-control @error('field_name') is-invalid @enderror" value="{{ old('field_name', $formField->field_name) }}" placeholder="e.g., phone_number" required>
                                <div class="form-text">Nama field untuk database (gunakan snake_case)</div>
                                @error('field_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="field_label" class="form-label required">Field Label</label>
                                <input type="text" name="field_label" id="field_label" class="form-control @error('field_label') is-invalid @enderror" value="{{ old('field_label', $formField->field_label) }}" placeholder="e.g., Nomor Telepon" required>
                                <div class="form-text">Label yang ditampilkan di form</div>
                                @error('field_label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <label for="placeholder" class="form-label">Placeholder</label>
                            <input type="text" name="placeholder" id="placeholder" class="form-control @error('placeholder') is-invalid @enderror" value="{{ old('placeholder', $formField->placeholder) }}" placeholder="Teks placeholder untuk field">
                            @error('placeholder')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mt-3">
                            <label for="help_text" class="form-label">Help Text</label>
                            <textarea name="help_text" id="help_text" class="form-control @error('help_text') is-invalid @enderror" rows="2" placeholder="Teks bantuan untuk field ini">{{ old('help_text', $formField->help_text) }}</textarea>
                            @error('help_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Options Section (for select, radio, checkbox) -->
                    <div class="form-section mt-4" id="options-section" style="display: {{ in_array($formField->field_type, ['select', 'radio', 'checkbox']) ? 'block' : 'none' }};">
                        <h5 class="section-title">Opsi Field</h5>
                        <div class="mb-3">
                            <label for="field_options" class="form-label">Opsi (satu per baris)</label>
                            <textarea name="field_options" id="field_options" class="form-control @error('field_options') is-invalid @enderror" rows="5" placeholder="Opsi 1&#10;Opsi 2&#10;Opsi 3">{{ old('field_options', is_array($formField->field_options) ? implode("\n", $formField->field_options) : $formField->field_options) }}</textarea>
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
                                <input type="text" name="validation_rules" id="validation_rules" class="form-control @error('validation_rules') is-invalid @enderror" value="{{ old('validation_rules', $formField->validation_rules) }}" placeholder="e.g., min:3|max:50">
                                <div class="form-text">Aturan validasi Laravel (pisahkan dengan |)</div>
                                @error('validation_rules')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="field_order" class="form-label">Urutan</label>
                                <input type="number" name="field_order" id="field_order" class="form-control @error('field_order') is-invalid @enderror" value="{{ old('field_order', $formField->field_order) }}" min="1">
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
                            <input class="form-check-input" type="checkbox" name="is_required" id="is_required" value="1" {{ old('is_required', $formField->is_required) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_required">
                                Field Wajib Diisi
                            </label>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $formField->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Field Aktif
                            </label>
                        </div>
                    </div>
                    
                    <!-- Info Section -->
                    <div class="form-section mt-4">
                        <h5 class="section-title">Informasi</h5>
                        <div class="info-item">
                            <small class="text-muted">Dibuat:</small>
                            <div>{{ $formField->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div class="info-item mt-2">
                            <small class="text-muted">Terakhir diupdate:</small>
                            <div>{{ $formField->updated_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                    
                    <!-- Preview Section -->
                    <div class="form-section mt-4">
                        <h5 class="section-title">Preview</h5>
                        <div id="field-preview" class="preview-container">
                            <!-- Preview will be generated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Form Field
                </button>
                <a href="{{ route('admin.form-fields.show', $formField) }}" class="btn btn-outline-info">
                    <i class="fas fa-eye"></i>
                    View
                </a>
                <a href="{{ route('admin.form-fields.index') }}" class="btn btn-outline-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-section {
        background: rgba(255, 255, 255, 0.02);
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .section-title {
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }
    
    .required::after {
        content: ' *';
        color: #dc3545;
    }
    
    .preview-container {
        background: rgba(255, 255, 255, 0.05);
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        min-height: 100px;
    }
    
    .form-actions {
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .form-text {
        color: #adb5bd;
        font-size: 0.875rem;
    }
    
    .info-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fieldTypeSelect = document.getElementById('field_type');
        const fieldLabelInput = document.getElementById('field_label');
        const placeholderInput = document.getElementById('placeholder');
        const fieldOptionsTextarea = document.getElementById('field_options');
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
        
        // Update preview when inputs change
        [fieldTypeSelect, fieldLabelInput, placeholderInput, fieldOptionsTextarea, isRequiredCheck].forEach(element => {
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