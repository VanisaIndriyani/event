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
            <div class="d-flex gap-2">
                <div class="search-container">
                    <div class="input-group" style="width: 300px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               id="searchInput" 
                               class="form-control border-start-0" 
                               placeholder="Cari event atau field..." 
                               autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <a href="{{ route('admin.form-fields.create') }}{{ $eventId ? '?event_id=' . $eventId : '' }}" class="btn btn-light btn-lg">
                    <i class="fas fa-plus me-2"></i>Add Custom Field
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Add Section -->
    <div class="content-card">
        <h5 class="mb-3">
            <i class="fas fa-bolt me-2 text-warning"></i>Quick Add Common Fields
        </h5>
        <p class="text-white mb-4">Tambah field umum dengan satu klik. Field akan ditambahkan ke event yang dipilih.</p>
        
        <!-- Event-based Form Fields Management -->
        @php
            $events = \App\Models\Event::with(['formFields' => function($query) {
                $query->active()->ordered();
            }])->get();
        @endphp
        
        @if($events->count() > 0)
            @foreach($events as $event)
                <div class="event-section mb-4">
                    <div class="event-header d-flex justify-content-between align-items-center p-3 bg-gradient-primary text-white rounded-top">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-calendar-alt me-2"></i>
                                {{ $event->title }}
                            </h5>
                            <small class="opacity-75">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $event->location }} • 
                                <i class="fas fa-clock me-1"></i>
                                {{ $event->event_date->format('d M Y') }}
                            </small>
                        </div>
                        <div class="event-stats">
                            <span class="badge bg-light text-dark me-2">
                                {{ $event->formFields->count() }} Fields
                            </span>
                            <span class="badge {{ $event->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $event->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="event-content bg-white border border-top-0 rounded-bottom p-3">
                        <!-- Quick Add Buttons for this Event -->
                        <div class="quick-add-section mb-3">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-plus-circle me-2"></i>
                                Quick Add Fields
                            </h6>
                            <div class="row g-2">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-primary w-100 quick-add-btn" 
                                            data-event-id="{{ $event->id }}"
                                            data-type="text" 
                                            data-name="nama_lengkap" 
                                            data-label="Nama Lengkap" 
                                            data-required="1">
                                        <i class="fas fa-user mb-1"></i>
                                        <div class="small">Nama Lengkap</div>
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-success w-100 quick-add-btn" 
                                            data-event-id="{{ $event->id }}"
                                            data-type="email" 
                                            data-name="email" 
                                            data-label="Email Address" 
                                            data-required="1">
                                        <i class="fas fa-envelope mb-1"></i>
                                        <div class="small">Email</div>
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-info w-100 quick-add-btn" 
                                            data-event-id="{{ $event->id }}"
                                            data-type="text" 
                                            data-name="no_whatsapp" 
                                            data-label="Nomor WhatsApp" 
                                            data-required="1">
                                        <i class="fab fa-whatsapp mb-1"></i>
                                        <div class="small">WhatsApp</div>
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-warning w-100 quick-add-btn" 
                                            data-event-id="{{ $event->id }}"
                                            data-type="file" 
                                            data-name="foto_profil" 
                                            data-label="Foto Profil" 
                                            data-required="0">
                                        <i class="fas fa-camera mb-1"></i>
                                        <div class="small">Foto</div>
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-secondary w-100 quick-add-btn" 
                                            data-event-id="{{ $event->id }}"
                                            data-type="number" 
                                            data-name="usia" 
                                            data-label="Usia" 
                                            data-required="0">
                                        <i class="fas fa-birthday-cake mb-1"></i>
                                        <div class="small">Usia</div>
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('admin.form-fields.create', ['event_id' => $event->id]) }}" 
                                       class="btn btn-outline-purple w-100">
                                        <i class="fas fa-plus mb-1"></i>
                                        <div class="small">Custom Field</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Existing Fields for this Event -->
                        @if($event->formFields->count() > 0)
                            <div class="existing-fields">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-list me-2"></i>
                                    Existing Fields ({{ $event->formFields->count() }})
                                </h6>
                                <div class="row g-2">
                                    @foreach($event->formFields as $field)
                                        <div class="col-md-3">
                                            <div class="field-card p-2 border rounded {{ $field->is_active ? 'border-success' : 'border-secondary' }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold small">{{ $field->field_label }}</div>
                                                        <div class="text-muted small">{{ $field->field_name }}</div>
                                                        <div class="small">
                                                            <span class="badge bg-light text-dark">{{ ucfirst($field->field_type) }}</span>
                                                            @if($field->is_required)
                                                                <span class="badge bg-danger">Required</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="{{ route('admin.form-fields.show', $field->id) }}"><i class="fas fa-eye me-2"></i>View</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.form-fields.edit', $field->id) }}"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteField({{ $field->id }})"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                                <h6 class="text-muted mt-2">No form fields yet</h6>
                                <p class="text-muted small">Use the quick add buttons above to add form fields to this event.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">No Events Found</h5>
                <p class="text-muted">Create an event first before managing form fields.</p>
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Event
                </a>
            </div>
        @endif
    </div>

   

  
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary-navy: #0B1426;
        --secondary-navy: #1A2332;
        --accent-blue: #2563EB;
        --bright-blue: #3B82F6;
        --cyan: #06B6D4;
        --purple: #8B5CF6;
        --text-light: #F8FAFC;
        --text-gray: #94A3B8;
        --border-color: #334155;
        --glass-bg: rgba(59, 130, 246, 0.3);
        --gradient-primary: linear-gradient(135deg, #0B1426 0%, #1A2332 50%, #2563EB 100%);
        --gradient-accent: linear-gradient(135deg, #3B82F6 0%, #06B6D4 50%, #8B5CF6 100%);
    }
    
    /* Override admin layout for this page */
    .admin-content {
        background: var(--primary-navy);
        min-height: 100vh;
        padding: 2rem;
        color: var(--text-light);
    }
    
    .content-header {
        background: var(--gradient-primary);
        color: var(--text-light);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(10px);
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-light);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .page-subtitle {
        color: var(--text-gray);
        font-size: 1.1rem;
    }
    
    .content-card {
        background: var(--glass-bg);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(10px);
        color: var(--text-light);
    }
    
    /* Event Section Styling */
    .event-section {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
    }
    
    .event-section:hover {
        box-shadow: 0 12px 48px rgba(59, 130, 246, 0.2);
        transform: translateY(-4px);
        border-color: var(--accent-blue);
    }
    
    .event-header {
        background: var(--gradient-accent);
        color: var(--text-light);
    }
    
    .bg-gradient-primary {
        background: var(--gradient-accent) !important;
    }
    
    /* Quick Add Buttons Styling */
    .quick-add-btn {
        height: 80px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        background: var(--glass-bg);
        border: 1px solid var(--border-color);
        color: var(--text-light);
        backdrop-filter: blur(10px);
    }
    
    .quick-add-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
        border-color: var(--accent-blue);
        background: rgba(59, 130, 246, 0.1);
        color: var(--text-light);
    }
    
    .quick-add-btn i {
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
    }
    
    .quick-add-btn .small {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-outline-purple {
        color: #6f42c1;
        border-color: #6f42c1;
    }
    
    .btn-outline-purple:hover {
        color: white;
        background-color: #6f42c1;
        border-color: #6f42c1;
    }
    
    /* Field Card Styling */
    .field-card {
        transition: all 0.3s ease;
        background: var(--glass-bg);
        border: 1px solid var(--border-color) !important;
        color: var(--text-light);
        backdrop-filter: blur(10px);
    }
    
    .field-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.2);
        border-color: var(--accent-blue) !important;
    }
    
    .field-card .fw-bold {
        color: var(--text-light);
    }
    
    .field-card .text-muted {
        color: var(--text-gray) !important;
    }
    
    .event-stats .badge {
        font-size: 0.75rem;
    }
    
    .existing-fields h6 {
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
    }
    
    .quick-add-section h6 {
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
    }
    
    /* Loading state */
    .quick-add-btn.loading {
        pointer-events: none;
        opacity: 0.6;
    }
    
    .quick-add-btn.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Success state */
    .quick-add-btn.success {
        background-color: #10b981;
        border-color: #10b981;
        color: white;
    }
    
    .event-content {
        background: var(--glass-bg);
        border: 1px solid var(--border-color);
        color: var(--text-light);
        backdrop-filter: blur(10px);
    }
    
    .filter-form {
        background: var(--glass-bg);
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        color: var(--text-light);
        backdrop-filter: blur(10px);
    }
    
    .form-label {
        color: #1f2937;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .form-select {
        background: var(--glass-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        color: var(--text-light);
        backdrop-filter: blur(10px);
    }
    
    .form-select:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        background: var(--glass-bg);
        color: var(--text-light);
    }
    
    .form-control {
        background: var(--glass-bg);
        border: 1px solid var(--border-color);
        color: var(--text-light);
        backdrop-filter: blur(10px);
    }
    
    .form-control:focus {
        background: var(--glass-bg);
        border-color: var(--accent-blue);
        color: var(--text-light);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    
    .form-control::placeholder {
        color: var(--text-gray);
    }
    
    .btn-primary {
        background: var(--gradient-accent);
        border: none;
        color: var(--text-light);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
    }
    
    .btn-primary:hover {
        background: var(--gradient-primary);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
        color: var(--text-light);
    }
    
    .btn-light {
        background: var(--glass-bg);
        border: 1px solid var(--border-color);
        color: var(--text-light);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-light:hover {
        background: var(--accent-blue);
        border-color: var(--accent-blue);
        color: var(--text-light);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
    }
    
    .btn-outline-secondary {
        border: 1px solid var(--border-color);
        color: var(--text-gray);
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border-radius: 8px;
    }
    
    .btn-outline-secondary:hover {
        background: var(--accent-blue);
        border-color: var(--accent-blue);
        color: var(--text-light);
    }
    
    .table {
        background: var(--glass-bg);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
    }
    
    .table th {
        background: var(--primary-navy);
        color: var(--text-light);
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
        border-bottom: 2px solid var(--border-color);
    }
    
    .table td {
        border-color: var(--border-color);
        vertical-align: middle;
        padding: 1rem 0.75rem;
        color: var(--text-light);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    .drag-handle {
        color: #6b7280;
        margin-right: 0.5rem;
        cursor: move;
    }
    
    .drag-handle:hover {
        color: #3b82f6;
    }
    
    .sortable-ghost {
        opacity: 0.4;
    }
    
    .sortable-chosen {
        background-color: #f8f9fa;
    }
    
    .drag-handle {
        cursor: grab;
    }
    
    .drag-handle:active {
        cursor: grabbing;
    }
    
    /* Search Input Styling */
     .search-container .input-group {
         box-shadow: 0 8px 32px rgba(0,0,0,0.3);
         border-radius: 12px;
         overflow: hidden;
         backdrop-filter: blur(10px);
     }
     
     .search-container .input-group-text {
         background: var(--glass-bg);
         border: 1px solid var(--border-color);
         color: var(--text-gray);
         backdrop-filter: blur(10px);
     }
     
     .search-container .form-control {
         border: 1px solid var(--border-color);
         background: var(--glass-bg);
         color: var(--text-light);
         backdrop-filter: blur(10px);
         padding: 0.75rem 1rem;
     }
     
     .search-container .form-control:focus {
         border-color: var(--accent-blue);
         box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
         background: var(--glass-bg);
         color: var(--text-light);
     }
     
     .search-container .form-control::placeholder {
         color: var(--text-gray);
     }
     
     .search-container .btn {
         border: 1px solid var(--border-color);
         background: var(--glass-bg);
         color: var(--text-light);
         backdrop-filter: blur(10px);
     }
     
     .search-container .btn:hover {
         background: var(--accent-blue);
         border-color: var(--accent-blue);
         color: var(--text-light);
     }
     
     #noSearchResults {
         background: #f8f9fa;
         border-radius: 12px;
         margin: 2rem 0;
     }
     
     /* Responsive Design */
     @media (max-width: 768px) {
         .quick-add-btn {
             height: 60px;
         }
         
         .quick-add-btn i {
             font-size: 1.2rem;
         }
         
         .event-header h5 {
             font-size: 1rem;
         }
         
         .event-header small {
             font-size: 0.7rem;
         }
         
         .content-card {
             padding: 1rem;
         }
         
         .admin-content {
             padding: 1rem;
         }
         
         .search-container .input-group {
             width: 100% !important;
             margin-bottom: 1rem;
         }
         
         .d-flex.gap-2 {
             flex-direction: column;
         }
     }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }
    
    .badge.bg-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: var(--text-light) !important;
    }
    
    .badge.bg-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        color: var(--text-light) !important;
    }
    
    .badge.bg-info {
        background: var(--gradient-accent) !important;
        color: var(--text-light) !important;
    }
    
    .badge.bg-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
        color: var(--text-light) !important;
    }
    
    .badge.bg-light {
        background: var(--glass-bg) !important;
        color: var(--text-light) !important;
        border: 1px solid var(--border-color);
    }
    
    .bg-danger {
        background-color: #ef4444 !important;
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
$(document).ready(function() {
    // Quick Add Field functionality
    $('.quick-add-btn').click(function(e) {
        e.preventDefault();
        
        const button = $(this);
        const eventId = button.data('event-id');
        
        // Check if event is selected
        if (!eventId) {
            alert('Event ID tidak ditemukan!');
            return;
        }
        
        // Get field data from button attributes
        const fieldData = {
            event_id: eventId,
            field_type: button.data('type'),
            field_name: button.data('name'),
            field_label: button.data('label'),
            is_required: button.data('required'),
            options: button.data('options') || null,
            is_active: 1,
            sort_order: 999 // Will be adjusted by backend
        };
        
        console.log('Field data:', fieldData); // Debug log
        
        // Add loading state
        button.addClass('loading');
        button.prop('disabled', true);
        
        // Send AJAX request
        $.ajax({
            url: '{{ route("admin.form-fields.store") }}',
            method: 'POST',
            data: fieldData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Show success state
                button.removeClass('loading').addClass('success');
                
                // Show success message
                if (response.message) {
                    // Create success alert
                    const alert = $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        '<i class="fas fa-check-circle me-2"></i>' + response.message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>');
                    
                    $('.content-card').first().prepend(alert);
                    
                    // Auto dismiss after 3 seconds
                    setTimeout(function() {
                        alert.fadeOut();
                    }, 3000);
                }
                
                // Reset button after 2 seconds
                setTimeout(function() {
                    button.removeClass('success').prop('disabled', false);
                }, 2000);
                
                // Reload page to show new field
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr) {
                // Remove loading state
                button.removeClass('loading').prop('disabled', false);
                
                let errorMessage = 'Terjadi kesalahan saat menambah field.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join(', ');
                }
                
                // Show error alert
                const alert = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    '<i class="fas fa-exclamation-triangle me-2"></i>' + errorMessage +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                    '</div>');
                
                $('.content-card').first().prepend(alert);
                
                // Auto dismiss after 5 seconds
                setTimeout(function() {
                    alert.fadeOut();
                }, 5000);
            }
        });
    }
    
    // Search functionality
    $('#searchInput').on('input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        const clearButton = $('#clearSearch');
        
        if (searchTerm.length > 0) {
            clearButton.show();
            filterEventSections(searchTerm);
        } else {
            clearButton.hide();
            showAllEventSections();
        }
    });
    
    $('#clearSearch').on('click', function() {
        $('#searchInput').val('');
        $(this).hide();
        showAllEventSections();
    });
    
    // Initialize on page load
    initializeSortable();
});

// Initialize sortable when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeSortable();
    });
    
    // Add showAlert function for notifications
     function showAlert(type, message) {
         const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
         const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
         
         const alert = $('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
             '<i class="' + icon + ' me-2"></i>' + message +
             '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
             '</div>');
         
         $('.content-card').first().prepend(alert);
         
         // Auto dismiss after 5 seconds
         setTimeout(function() {
             alert.fadeOut();
         }, 5000);
     }
     
     // Delete field function
     function deleteField(fieldId) {
         if (confirm('Apakah Anda yakin ingin menghapus field ini?')) {
             $.ajax({
                 url: '/admin/form-fields/' + fieldId,
                 method: 'DELETE',
                 data: {
                     _token: '{{ csrf_token() }}'
                 },
                 success: function(response) {
                     if (response.success) {
                         showAlert('success', 'Field berhasil dihapus!');
                         setTimeout(() => {
                             window.location.reload();
                         }, 1000);
                     } else {
                         showAlert('error', response.message || 'Terjadi kesalahan');
                     }
                 },
                 error: function(xhr) {
                     let errorMessage = 'Terjadi kesalahan';
                     if (xhr.responseJSON && xhr.responseJSON.message) {
                         errorMessage = xhr.responseJSON.message;
                     }
                     showAlert('error', errorMessage);
                 }
             });
         }
     }
     
     function filterEventSections(searchTerm) {
         let visibleSections = 0;
         
         $('.event-section').each(function() {
             const $section = $(this);
             const eventTitle = $section.find('.event-header h5').text().toLowerCase();
             const eventLocation = $section.find('.event-header small').text().toLowerCase();
             
             let hasMatchingFields = false;
             let visibleFields = 0;
             
             // Check if event title or location matches
             const eventMatches = eventTitle.includes(searchTerm) || eventLocation.includes(searchTerm);
             
             // Check fields within this event
             $section.find('.field-card').each(function() {
                 const $field = $(this);
                 const fieldLabel = $field.find('.fw-bold').text().toLowerCase();
                 const fieldName = $field.find('.text-muted').first().text().toLowerCase();
                 const fieldType = $field.find('.badge').first().text().toLowerCase();
                 
                 const fieldMatches = fieldLabel.includes(searchTerm) || 
                                    fieldName.includes(searchTerm) || 
                                    fieldType.includes(searchTerm);
                 
                 if (fieldMatches || eventMatches) {
                     $field.closest('.col-md-3').show();
                     hasMatchingFields = true;
                     visibleFields++;
                 } else {
                     $field.closest('.col-md-3').hide();
                 }
             });
             
             // Show/hide the entire event section
             if (eventMatches || hasMatchingFields) {
                 $section.show();
                 visibleSections++;
                 
                 // Update field count in header if fields are filtered
                 if (!eventMatches && hasMatchingFields) {
                     const $badge = $section.find('.event-stats .badge').first();
                     $badge.text(visibleFields + ' Fields (filtered)');
                 }
             } else {
                 $section.hide();
             }
         });
         
         // Show no results message if no sections are visible
         toggleNoResultsMessage(visibleSections === 0, searchTerm);
     }
     
     function showAllEventSections() {
         $('.event-section').show();
         $('.field-card').closest('.col-md-3').show();
         
         // Reset field counts in headers
         $('.event-section').each(function() {
             const $section = $(this);
             const totalFields = $section.find('.field-card').length;
             const $badge = $section.find('.event-stats .badge').first();
             $badge.text(totalFields + ' Fields');
         });
         
         toggleNoResultsMessage(false);
     }
     
     function toggleNoResultsMessage(show, searchTerm = '') {
         const $container = $('.content-card');
         const $noResults = $('#noSearchResults');
         
         if (show) {
             if ($noResults.length === 0) {
                 const noResultsHtml = `
                     <div id="noSearchResults" class="text-center py-5">
                         <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
                         <h5 class="text-muted mt-3">Tidak ada hasil ditemukan</h5>
                         <p class="text-muted">Tidak ada event atau field yang cocok dengan pencarian "<strong>${searchTerm}</strong>"</p>
                         <button class="btn btn-outline-primary" onclick="$('#searchInput').val(''); $('#clearSearch').click();">
                             <i class="fas fa-times me-2"></i>Hapus Pencarian
                         </button>
                     </div>
                 `;
                 $container.append(noResultsHtml);
             }
         } else {
             $noResults.remove();
         }
     }
    
    // Initialize sortable functionality
    function initializeSortable() {
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
    }
</script>
@endpush