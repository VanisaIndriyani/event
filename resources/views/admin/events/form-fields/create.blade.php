@extends('layouts.admin')

@section('title', 'Tambah Form Field - ' . $event->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Tambah Form Field</h4>
                        <small class="text-muted">Event: {{ $event->title }}</small>
                    </div>
                    <a href="{{ route('admin.events.form-fields.index', $event) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.events.form-fields.store', $event) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="field_name" class="form-label">Field Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('field_name') is-invalid @enderror" 
                                           id="field_name" name="field_name" value="{{ old('field_name') }}" 
                                           placeholder="e.g., phone_number">
                                    <div class="form-text">Nama field (tanpa spasi, gunakan underscore)</div>
                                    @error('field_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="field_label" class="form-label">Field Label <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('field_label') is-invalid @enderror" 
                                           id="field_label" name="field_label" value="{{ old('field_label') }}" 
                                           placeholder="e.g., Nomor Telepon">
                                    <div class="form-text">Label yang akan ditampilkan kepada user</div>
                                    @error('field_label')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="field_type" class="form-label">Field Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('field_type') is-invalid @enderror" 
                                            id="field_type" name="field_type">
                                        <option value="">Pilih tipe field</option>
                                        @foreach($fieldTypes as $value => $label)
                                            <option value="{{ $value }}" {{ old('field_type') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('field_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Urutan</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" 
                                           min="0">
                                    <div class="form-text">Urutan tampil field (0 = paling atas)</div>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3" id="field-options-container" style="display: none;">
                            <label for="field_options" class="form-label">Field Options</label>
                            <div id="options-list">
                                <!-- Options will be added dynamically -->
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-option">
                                <i class="fas fa-plus"></i> Tambah Option
                            </button>
                            <div class="form-text">Untuk field type 'select', tambahkan pilihan yang tersedia</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_required" 
                                           name="is_required" value="1" {{ old('is_required') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_required">
                                        Field Wajib Diisi
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.events.form-fields.index', $event) }}" class="btn btn-secondary me-2">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Field
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fieldTypeSelect = document.getElementById('field_type');
    const optionsContainer = document.getElementById('field-options-container');
    const optionsList = document.getElementById('options-list');
    const addOptionBtn = document.getElementById('add-option');
    
    let optionIndex = 0;
    
    // Show/hide options container based on field type
    fieldTypeSelect.addEventListener('change', function() {
        if (this.value === 'select') {
            optionsContainer.style.display = 'block';
        } else {
            optionsContainer.style.display = 'none';
            optionsList.innerHTML = '';
            optionIndex = 0;
        }
    });
    
    // Add option input
    addOptionBtn.addEventListener('click', function() {
        const optionDiv = document.createElement('div');
        optionDiv.className = 'input-group mb-2';
        optionDiv.innerHTML = `
            <input type="text" class="form-control" name="field_options[]" placeholder="Option ${optionIndex + 1}">
            <button type="button" class="btn btn-outline-danger remove-option">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        optionsList.appendChild(optionDiv);
        optionIndex++;
        
        // Add remove functionality
        optionDiv.querySelector('.remove-option').addEventListener('click', function() {
            optionDiv.remove();
        });
    });
    
    // Auto-generate field name from label
    document.getElementById('field_label').addEventListener('input', function() {
        const fieldNameInput = document.getElementById('field_name');
        if (!fieldNameInput.value) {
            const fieldName = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '_')
                .replace(/^_+|_+$/g, '');
            fieldNameInput.value = fieldName;
        }
    });
});
</script>
@endpush