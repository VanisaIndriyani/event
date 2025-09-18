@extends('layouts.admin')

@section('title', 'Add New Merchandise')

@push('styles')
<style>
    .form-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        color: #e0e6ed;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        font-family: 'Orbitron', monospace;
    }
    
    .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        color: #fff;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        color: #fff;
    }
    
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        border-radius: 8px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
    }
    
    .btn-secondary {
        background: rgba(108, 117, 125, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 12px 30px;
        font-weight: 600;
        color: #fff;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: rgba(108, 117, 125, 1);
        transform: translateY(-2px);
        color: #fff;
    }
    
    .invalid-feedback {
        color: #ff6b6b;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .image-preview {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        margin-top: 10px;
    }
    
    /* Special styling for select dropdown */
    select.form-control {
        background: rgba(0, 0, 0, 0.6) !important;
        color: #fff !important;
    }
    
    select.form-control option {
        background: rgba(0, 0, 0, 0.9) !important;
        color: #fff !important;
        padding: 8px;
    }
    
    select.form-control:focus {
        background: rgba(0, 0, 0, 0.7) !important;
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        color: #fff !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-white mb-1" style="font-family: 'Orbitron', monospace;">Add New Merchandise</h2>
            <p class="text-white mb-0">Create a new merchandise item for your store</p>
        </div>
        <div>
            <a href="{{ route('admin.merchandise.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="form-container">
        <form action="{{ route('admin.merchandise.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name" class="form-label">Merchandise Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="Enter merchandise name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Enter merchandise description" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="form-label">Price (Rp) *</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" 
                                       placeholder="0" min="0" step="0.01" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock" class="form-label">Stock Quantity *</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock') }}" 
                                       placeholder="0" min="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-control @error('category') is-invalid @enderror" id="category" name="category">
                            <option value="">Select Category</option>
                            <option value="clothing" {{ old('category') == 'clothing' ? 'selected' : '' }}>Clothing</option>
                            <option value="accessories" {{ old('category') == 'accessories' ? 'selected' : '' }}>Accessories</option>
                            <option value="tech" {{ old('category') == 'tech' ? 'selected' : '' }}>Tech Items</option>
                            <option value="collectibles" {{ old('category') == 'collectibles' ? 'selected' : '' }}>Collectibles</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="image" class="form-label">Merchandise Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <img id="preview" class="image-preview" alt="Preview">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.merchandise.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Create Merchandise
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection