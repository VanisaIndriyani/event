@extends('layouts.admin')

@section('title', 'Create New Event')

@push('styles')
<style>
    :root {
        --primary-navy: #0B1426;
        --secondary-navy: #1A2332;
        --accent-color: #3B82F6;
        --glass-bg: rgba(15, 23, 42, 0.8);
        --glass-border: rgba(255, 255, 255, 0.1);
        --text-primary: #FFFFFF;
        --text-secondary: #E2E8F0;
        --text-light: #F8FAFC;
        --gradient-accent: linear-gradient(135deg, #3B82F6 0%, #06B6D4 50%, #8B5CF6 100%);
        --gradient-start: #0B1426;
        --gradient-end: #1A2332;
        --shadow-glass: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
    
    .create-event-container {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .create-event-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-glass);
        max-width: 800px;
        margin: 0 auto;
    }
    
    .page-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1.1rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: rgba(255, 255, 255, 0.15);
    }
    
    .form-control::placeholder {
        color: #94A3B8;
        opacity: 0.8;
    }
    
    .form-control {
        color: var(--text-primary) !important;
    }
    
    .form-control::-webkit-input-placeholder {
        color: #94A3B8;
        opacity: 0.8;
    }
    
    /* Image Preview Styles */
    .image-preview-container {
        margin-top: 1rem;
    }
    
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .preview-item {
        position: relative;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        overflow: hidden;
        aspect-ratio: 1;
    }
    
    .preview-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    .preview-remove {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
    }
    
    .preview-remove:hover {
        background: rgba(220, 53, 69, 1);
        transform: scale(1.1);
    }
    
    .preview-filename {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px;
        font-size: 11px;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .form-control::-moz-placeholder {
        color: #94A3B8;
        opacity: 0.8;
    }
    
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .btn-group {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .btn {
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 1rem;
    }
    
    .btn-primary {
        background: var(--gradient-accent);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
    }
    
    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
        border: 1px solid var(--glass-border);
    }
    
    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }
    
    .alert {
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    
    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }
    
    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .form-control.is-invalid {
        border-color: #ef4444;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .create-event-card {
            margin: 1rem;
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="create-event-container">
    <div class="create-event-card">
        <div class="page-header">
            <h1 class="page-title">Create New Event</h1>
            <p class="page-subtitle">Fill in the details to create a new event</p>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="title" class="form-label">Event Title *</label>
                <input type="text" 
                       class="form-control @error('title') is-invalid @enderror" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}" 
                       placeholder="Enter event title"
                       required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          placeholder="Enter event description"
                          required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="event_date" class="form-label">Event Date *</label>
                    <input type="datetime-local" 
                           class="form-control @error('event_date') is-invalid @enderror" 
                           id="event_date" 
                           name="event_date" 
                           value="{{ old('event_date') }}"
                           required>
                    @error('event_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="location" class="form-label">Location *</label>
                    <input type="text" 
                           class="form-control @error('location') is-invalid @enderror" 
                           id="location" 
                           name="location" 
                           value="{{ old('location') }}" 
                           placeholder="Enter event location"
                           required>
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="max_participants" class="form-label">Max Participants</label>
                    <input type="number" 
                           class="form-control @error('max_participants') is-invalid @enderror" 
                           id="max_participants" 
                           name="max_participants" 
                           value="{{ old('max_participants') }}" 
                           placeholder="Enter maximum participants"
                           min="1">
                    @error('max_participants')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="price" class="form-label">Price (Rp)</label>
                    <input type="number" 
                           class="form-control @error('price') is-invalid @enderror" 
                           id="price" 
                           name="price" 
                           value="{{ old('price') }}" 
                           placeholder="Enter event price"
                           min="0"
                           step="0.01">
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="images" class="form-label">Event Images</label>
                <input type="file" 
                       class="form-control @error('images') is-invalid @enderror" 
                       id="images" 
                       name="images[]" 
                       accept="image/*"
                       multiple
                       onchange="previewImages(this)"
                       max="5">
                @error('images')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-white">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB per image. Maximum 5 images allowed.</small>
                
                <!-- Image Preview Container -->
                <div id="imagePreviewContainer" class="image-preview-container mt-3" style="display: none;">
                    <div class="preview-grid" id="previewGrid"></div>
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Create Event
                </button>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Events
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let selectedFiles = [];

function previewImages(input) {
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewGrid = document.getElementById('previewGrid');
    
    if (input.files && input.files.length > 0) {
        if (input.files.length > 5) {
            alert('Maksimal 5 foto yang dapat diupload!');
            input.value = '';
            return;
        }
        
        selectedFiles = Array.from(input.files);
        previewGrid.innerHTML = '';
        previewContainer.style.display = 'block';
        
        selectedFiles.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="preview-image">
                        <button type="button" class="preview-remove" onclick="removeImage(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="preview-filename">${file.name}</div>
                    `;
                    previewGrid.appendChild(previewItem);
                };
                
                reader.readAsDataURL(file);
            }
        });
    } else {
        previewContainer.style.display = 'none';
        selectedFiles = [];
    }
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    
    // Update the file input
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    input.files = dt.files;
    
    // Refresh preview
    if (selectedFiles.length > 0) {
        previewImages(input);
    } else {
        document.getElementById('imagePreviewContainer').style.display = 'none';
    }
}
</script>
@endsection