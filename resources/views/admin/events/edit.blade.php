@extends('layouts.admin')

@section('title', 'Edit Event')

@push('styles')
<style>
    :root {
        --primary-navy: #0B1426;
        --secondary-navy: #1A2332;
        --accent-color: #3B82F6;
        --accent-blue: #2563EB;
        --glass-bg: rgba(15, 23, 42, 0.8);
        --glass-border: rgba(255, 255, 255, 0.1);
        --text-primary: #FFFFFF;
        --text-secondary: #E2E8F0;
        --text-light: #F8FAFC;
        --gradient-accent: linear-gradient(135deg, #3B82F6 0%, #06B6D4 50%, #8B5CF6 100%);
    }
    .edit-event {
        padding: 2rem 0;
        min-height: calc(100vh - 80px);
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        font-family: 'Orbitron', monospace;
        font-size: 2.5rem;
        font-weight: 700;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }
    
    .form-container {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    
    .form-label {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .form-control {
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: var(--text-primary) !important;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control::placeholder {
        color: #94A3B8;
        opacity: 0.8;
    }
    
    .form-control::-webkit-input-placeholder {
        color: #94A3B8;
        opacity: 0.8;
    }
    
    .form-control::-moz-placeholder {
        color: #94A3B8;
        opacity: 0.8;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        background: rgba(255, 255, 255, 0.08);
    }
    
    .form-control.textarea {
        min-height: 120px;
        resize: vertical;
    }
    
    .btn-group {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }
    
    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: var(--gradient-accent);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(59, 130, 246, 0.3);
    }
    
    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-light);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        text-decoration: none;
    }
    
    .error-message {
        color: #EF4444;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    
    .current-image {
        max-width: 200px;
        border-radius: 8px;
        margin-top: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="edit-event">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Edit Event</h1>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Events
            </a>
        </div>
        
        <!-- Form Container -->
        <div class="form-container">
            <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <!-- Event Title -->
                    <div class="form-group">
                        <label class="form-label" for="title">Event Title *</label>
                        <input type="text" id="title" name="title" class="form-control" 
                               value="{{ old('title', $event->title) }}" required>
                        @error('title')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Event Date -->
                    <div class="form-group">
                        <label class="form-label" for="event_date">Event Date *</label>
                        <input type="datetime-local" id="event_date" name="event_date" class="form-control" 
                               value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required>
                        @error('event_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Location -->
                    <div class="form-group">
                        <label class="form-label" for="location">Location *</label>
                        <input type="text" id="location" name="location" class="form-control" 
                               value="{{ old('location', $event->location) }}" required>
                        @error('location')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Max Participants -->
                    <div class="form-group">
                        <label class="form-label" for="max_participants">Max Participants</label>
                        <input type="number" id="max_participants" name="max_participants" class="form-control" 
                               value="{{ old('max_participants', $event->max_participants) }}" min="1">
                        @error('max_participants')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Price -->
                    <div class="form-group">
                        <label class="form-label" for="price">Price (IDR)</label>
                        <input type="number" id="price" name="price" class="form-control" 
                               value="{{ old('price', $event->price) }}" min="0" step="0.01">
                        @error('price')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Is Active -->
                    <div class="form-group">
                        <label class="form-label" for="is_active">Status</label>
                        <select id="is_active" name="is_active" class="form-control">
                            <option value="1" {{ old('is_active', $event->is_active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $event->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="form-group full-width">
                        <label class="form-label" for="description">Description *</label>
                        <textarea id="description" name="description" class="form-control textarea" 
                                  required>{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Event Image -->
                    <div class="form-group full-width">
                        <label class="form-label" for="image">Event Image</label>
                        <input type="file" id="image" name="image" class="form-control" 
                               accept="image/jpeg,image/png,image/jpg,image/gif">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="Current Image" class="current-image">
                            <p style="color: var(--text-gray); font-size: 0.85rem; margin-top: 0.5rem;">Current image (leave empty to keep current image)</p>
                        @endif
                        @error('image')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="btn-group">
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection