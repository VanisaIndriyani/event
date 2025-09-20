@extends('layouts.admin')

@section('title', 'Edit Portfolio')

@section('content')
<div class="portfolio-edit">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Edit Portfolio
        </h1>
        <p class="page-description">Edit portfolio: {{ $portfolio->title }}</p>
        <div class="page-actions">
            <a href="{{ route('admin.portfolios.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
            <a href="{{ route('admin.portfolios.show', $portfolio) }}" class="btn btn-info">
                <i class="fas fa-eye"></i>
                Lihat
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('admin.portfolios.update', $portfolio) }}" method="POST" enctype="multipart/form-data" class="portfolio-form">
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Portfolio
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="event_id">Event <span class="required">*</span></label>
                        <select name="event_id" id="event_id" class="form-control" required>
                            <option value="">Pilih Event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ (old('event_id', $portfolio->event_id) == $event->id) ? 'selected' : '' }}>
                                    {{ $event->title }} - {{ $event->event_date->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status <span class="required">*</span></label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="draft" {{ old('status', $portfolio->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $portfolio->status) == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="title">Judul Portfolio <span class="required">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $portfolio->title) }}" required placeholder="Masukkan judul portfolio">
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Masukkan deskripsi portfolio (opsional)">{{ old('description', $portfolio->description) }}</textarea>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-images"></i>
                    Gambar Portfolio
                </h3>
                
                @if($portfolio->images && $portfolio->image_count > 0)
                    <div class="current-images">
                        <h4 class="subsection-title">Gambar Saat Ini</h4>
                        <div class="current-images-grid">
                            @foreach((is_array($portfolio->images) ? $portfolio->images : json_decode($portfolio->images, true)) as $index => $image)
                                <div class="current-image-item" data-index="{{ $index }}">
                                    <img src="{{ Storage::disk('public_uploads')->url($image) }}" alt="Portfolio Image {{ $index + 1 }}">
                                    <div class="image-actions">
                                        <button type="button" class="btn btn-sm btn-danger remove-current-image" data-index="{{ $index }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <div class="form-group">
                    <label for="images">Tambah Gambar Baru</label>
                    <div class="file-upload-area" id="fileUploadArea">
                        <div class="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt fa-3x"></i>
                            <h4>Drag & Drop gambar di sini</h4>
                            <p>atau klik untuk memilih file</p>
                            <small class="text-muted">Format: JPG, PNG, GIF (Max: 5MB per file)</small>
                        </div>
                        <input type="file" name="images[]" id="images" class="file-input" multiple accept="image/*">
                    </div>
                    @error('images')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div id="imagePreview" class="image-preview-container"></div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Portfolio
                </button>
                <a href="{{ route('admin.portfolios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.portfolio-edit {
    padding: 20px;
}

.form-container {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    padding: 30px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.form-section {
    margin-bottom: 30px;
}

.section-title {
    color: #fff;
    font-size: 1.2rem;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}

.subsection-title {
    color: #fff;
    font-size: 1rem;
    margin-bottom: 15px;
    font-weight: 600;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #fff;
    font-weight: 600;
}

.required {
    color: #ff5252;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    color: #ffffff !important;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #4fc3f7;
    box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.1);
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff !important;
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.form-control::-webkit-input-placeholder {
    color: rgba(255, 255, 255, 0.6) !important;
}

.form-control::-moz-placeholder {
    color: rgba(255, 255, 255, 0.6) !important;
}

.form-control:-ms-input-placeholder {
    color: rgba(255, 255, 255, 0.6) !important;
}

select.form-control {
    cursor: pointer;
}

select.form-control option {
    background: #1a1a1a;
    color: #fff;
}

.current-images {
    margin-bottom: 25px;
}

.current-images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
}

.current-image-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.current-image-item.removing {
    opacity: 0.5;
    transform: scale(0.95);
}

.current-image-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.image-actions {
    position: absolute;
    top: 5px;
    right: 5px;
}

.remove-current-image {
    background: rgba(255, 0, 0, 0.8);
    color: white;
    border: none;
    border-radius: 4px;
    padding: 5px 8px;
    cursor: pointer;
    font-size: 12px;
}

.remove-current-image:hover {
    background: rgba(255, 0, 0, 1);
}

.file-upload-area {
    position: relative;
    border: 2px dashed rgba(255, 255, 255, 0.3);
    border-radius: 10px;
    padding: 40px 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: #4fc3f7;
    background: rgba(79, 195, 247, 0.05);
}

.file-upload-area.dragover {
    border-color: #4fc3f7;
    background: rgba(79, 195, 247, 0.1);
}

.upload-placeholder h4 {
    color: #fff;
    margin: 15px 0 10px;
}

.upload-placeholder p {
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 10px;
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.image-preview-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.image-preview-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.1);
}

.image-preview-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.image-remove-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255, 0, 0, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.image-remove-btn:hover {
    background: rgba(255, 0, 0, 1);
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.error-message {
    color: #ff5252;
    font-size: 0.875rem;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .current-images-grid,
    .image-preview-container {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('images');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const imagePreview = document.getElementById('imagePreview');
    let selectedFiles = [];
    
    // Handle current image removal
    document.querySelectorAll('.remove-current-image').forEach(button => {
        button.addEventListener('click', function() {
            const index = this.dataset.index;
            const imageItem = this.closest('.current-image-item');
            const hiddenInput = imageItem.querySelector('input[type="hidden"]');
            
            if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                imageItem.classList.add('removing');
                hiddenInput.remove(); // Remove the hidden input so it won't be submitted
                
                setTimeout(() => {
                    imageItem.remove();
                }, 300);
            }
        });
    });
    
    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });
    
    // Handle drag and drop
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        fileUploadArea.classList.add('dragover');
    });
    
    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
    });
    
    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });
    
    function handleFiles(files) {
        for (let file of files) {
            if (file.type.startsWith('image/')) {
                selectedFiles.push(file);
                displayImagePreview(file);
            }
        }
        updateFileInput();
    }
    
    function displayImagePreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'image-preview-item';
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="image-remove-btn" onclick="removeImage(this, '${file.name}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
            imagePreview.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
    }
    
    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
    }
    
    window.removeImage = function(button, fileName) {
        selectedFiles = selectedFiles.filter(file => file.name !== fileName);
        button.parentElement.remove();
        updateFileInput();
    };
});
</script>
@endsection