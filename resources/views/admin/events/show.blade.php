@extends('layouts.admin')

@section('title', 'Event Details')

@push('styles')
<style>
    .event-details {
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
    
    .event-container {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .event-main {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 2rem;
    }
    
    .event-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .event-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }
    
    .event-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-light);
        margin-bottom: 1rem;
    }
    
    .event-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .meta-icon {
        width: 20px;
        height: 20px;
        color: var(--accent-blue);
    }
    
    .meta-content {
        flex: 1;
    }
    
    .meta-label {
        font-size: 0.85rem;
        color: var(--text-gray);
        margin-bottom: 0.25rem;
    }
    
    .meta-value {
        font-weight: 600;
        color: var(--text-light);
    }
    
    .event-description {
        color: var(--text-light);
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-active {
        background: rgba(16, 185, 129, 0.2);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .status-inactive {
        background: rgba(239, 68, 68, 0.2);
        color: #EF4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .stats-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
    }
    
    .stats-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 1rem;
    }
    
    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .stat-item:last-child {
        border-bottom: none;
    }
    
    .stat-label {
        color: var(--text-gray);
        font-size: 0.9rem;
    }
    
    .stat-value {
        color: var(--text-light);
        font-weight: 600;
    }
    
    .actions-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
    }
    
    .actions-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 1rem;
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
        padding: 0.75rem 1rem;
        margin-bottom: 0.75rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.9rem;
    }
    
    .action-btn:last-child {
        margin-bottom: 0;
    }
    
    .btn-edit {
        background: rgba(59, 130, 246, 0.2);
        color: #3B82F6;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }
    
    .btn-edit:hover {
        background: rgba(59, 130, 246, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-form-fields {
        background: rgba(16, 185, 129, 0.2);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .btn-form-fields:hover {
        background: rgba(16, 185, 129, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-delete {
        background: rgba(239, 68, 68, 0.2);
        color: #EF4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.3);
        color: white;
    }
    
    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-light);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        text-decoration: none;
    }
    
    @media (max-width: 768px) {
        .event-container {
            grid-template-columns: 1fr;
        }
        
        .event-meta {
            grid-template-columns: 1fr;
        }
        
        .page-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="event-details">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Event Details</h1>
            <a href="{{ route('admin.events.index') }}" class="action-btn btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to Events
            </a>
        </div>
        
        <!-- Event Container -->
        <div class="event-container">
            <!-- Main Content -->
            <div class="event-main">
                @if($event->images && count($event->images) > 0)
                    <img src="{{ asset('storage/' . $event->images[0]) }}" alt="{{ $event->title }}" class="event-image">
                @elseif($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="event-image">
                @endif
                
                <h2 class="event-title">{{ $event->title }}</h2>
                
                <!-- Event Meta Information -->
                <div class="event-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar meta-icon"></i>
                        <div class="meta-content">
                            <div class="meta-label">Event Date</div>
                            <div class="meta-value">{{ $event->event_date->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt meta-icon"></i>
                        <div class="meta-content">
                            <div class="meta-label">Location</div>
                            <div class="meta-value">{{ $event->location }}</div>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <i class="fas fa-users meta-icon"></i>
                        <div class="meta-content">
                            <div class="meta-label">Max Participants</div>
                            <div class="meta-value">{{ $event->max_participants ?? 'Unlimited' }}</div>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <i class="fas fa-tag meta-icon"></i>
                        <div class="meta-content">
                            <div class="meta-label">Price</div>
                            <div class="meta-value">{{ $event->price ? 'Rp ' . number_format($event->price, 0, ',', '.') : 'Free' }}</div>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <i class="fas fa-info-circle meta-icon"></i>
                        <div class="meta-content">
                            <div class="meta-label">Status</div>
                            <div class="meta-value">
                                <span class="status-badge {{ $event->is_active ? 'status-active' : 'status-inactive' }}">
                                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                    {{ $event->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <i class="fas fa-clock meta-icon"></i>
                        <div class="meta-content">
                            <div class="meta-label">Created</div>
                            <div class="meta-value">{{ $event->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Event Description -->
                <div class="event-description">
                    <h3 style="color: var(--text-light); margin-bottom: 1rem;">Description</h3>
                    <p>{{ $event->description }}</p>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="event-sidebar">
                <!-- Statistics -->
                <div class="stats-card">
                    <h3 class="stats-title">Event Statistics</h3>
                    <div class="stat-item">
                        <span class="stat-label">Total Registrations</span>
                        <span class="stat-value">{{ $event->registrations_count ?? 0 }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Available Spots</span>
                        <span class="stat-value">
                            @if($event->max_participants)
                                {{ $event->max_participants - ($event->registrations_count ?? 0) }}
                            @else
                                Unlimited
                            @endif
                        </span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Days Until Event</span>
                        <span class="stat-value">
                            @php
                                $daysUntil = now()->diffInDays($event->event_date, false);
                            @endphp
                            @if($daysUntil > 0)
                                {{ $daysUntil }} days
                            @elseif($daysUntil == 0)
                                Today
                            @else
                                {{ abs($daysUntil) }} days ago
                            @endif
                        </span>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="actions-card">
                    <h3 class="actions-title">Actions</h3>
                    <a href="{{ route('admin.events.edit', $event->id) }}" class="action-btn btn-edit">
                        <i class="fas fa-edit"></i>
                        Edit Event
                    </a>
                    <a href="{{ route('admin.events.form-fields.index', $event->id) }}" class="action-btn btn-form-fields">
                        <i class="fas fa-list-alt"></i>
                        Manage Form Fields
                    </a>
                    <button type="button" class="action-btn btn-delete" onclick="confirmDelete({{ $event->id }})">
                        <i class="fas fa-trash"></i>
                        Delete Event
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--glass-bg); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; max-width: 400px; margin: 2rem; text-align: center;">
        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #EF4444; margin-bottom: 1rem;"></i>
        <h3 style="color: var(--text-light); margin-bottom: 1rem;">Confirm Delete</h3>
        <p style="color: var(--text-gray); margin-bottom: 2rem;">Are you sure you want to delete this event? This action cannot be undone.</p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button type="button" onclick="closeDeleteModal()" style="padding: 0.75rem 1.5rem; background: rgba(255,255,255,0.1); color: var(--text-light); border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; cursor: pointer;">Cancel</button>
            <button type="button" onclick="executeDelete()" style="padding: 0.75rem 1.5rem; background: #EF4444; color: white; border: none; border-radius: 8px; cursor: pointer;">Delete</button>
        </div>
    </div>
</div>

<script>
function confirmDelete(eventId) {
    document.getElementById('delete-form').action = `/admin/events/${eventId}`;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

function executeDelete() {
    document.getElementById('delete-form').submit();
}
</script>
@endsection