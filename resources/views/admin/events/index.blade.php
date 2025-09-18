@extends('layouts.admin')

@section('title', 'Events Management')

@push('styles')
<style>
    .events-management {
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
    
    .page-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    .btn-primary {
        padding: 12px 24px;
        background: var(--gradient-accent);
        border: none;
        border-radius: 10px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(59, 130, 246, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .filters-section {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        background: rgba(0, 0, 0, 0.4);
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .filter-label {
        color: var(--text-light);
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .filter-control {
        padding: 10px 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: var(--text-light);
        font-size: 0.9rem;
    }
    
    .filter-control option {
        background: #1a1a1a;
        color: var(--text-light);
        padding: 8px;
    }
    
    .filter-control select {
        background: rgba(255, 255, 255, 0.05);
    }
    
    .filter-control:focus {
        outline: none;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
    }
    
    .btn-filter {
        padding: 10px 16px;
        background: rgba(59, 130, 246, 0.2);
        border: 1px solid var(--accent-blue);
        border-radius: 8px;
        color: var(--accent-blue);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-filter:hover {
        background: var(--accent-blue);
        color: white;
    }
    
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    
    .event-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }
    
    .event-image {
        height: 200px;
        background: var(--gradient-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        position: relative;
    }
    
    .event-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-active {
        background: rgba(16, 185, 129, 0.9);
        color: white;
    }
    
    .status-upcoming {
        background: rgba(59, 130, 246, 0.9);
        color: white;
    }
    
    .status-completed {
        background: rgba(107, 114, 128, 0.9);
        color: white;
    }
    
    .status-cancelled {
        background: rgba(239, 68, 68, 0.9);
        color: white;
    }
    
    .event-content {
        padding: 1.5rem;
    }
    
    .event-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-light);
        margin: 0 0 0.5rem 0;
        line-height: 1.3;
    }
    
    .event-date {
        color: var(--accent-blue);
        font-weight: 500;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .event-description {
        color: var(--text-gray);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .event-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-light);
        margin: 0;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--text-gray);
        margin: 0;
        text-transform: uppercase;
    }
    
    .event-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }
    
    .btn-edit {
        background: rgba(59, 130, 246, 0.2);
        color: var(--accent-blue);
        border: 1px solid var(--accent-blue);
    }
    
    .btn-edit:hover {
        background: var(--accent-blue);
        color: white;
        text-decoration: none;
    }
    
    .btn-delete {
        background: rgba(239, 68, 68, 0.2);
        color: #EF4444;
        border: 1px solid #EF4444;
    }
    
    .btn-delete:hover {
        background: #EF4444;
        color: white;
    }
    
    .btn-view {
        background: rgba(16, 185, 129, 0.2);
        color: #10B981;
        border: 1px solid #10B981;
    }
    
    .btn-view:hover {
        background: #10B981;
        color: white;
        text-decoration: none;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-gray);
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--accent-blue);
    }
    
    .empty-state h3 {
        color: var(--text-light);
        margin-bottom: 0.5rem;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }
    
    .page-btn {
        padding: 8px 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        color: var(--text-gray);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .page-btn:hover,
    .page-btn.active {
        background: var(--accent-blue);
        color: white;
        text-decoration: none;
    }
    
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    
    .modal.show {
        display: flex;
    }
    
    .modal-content {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 2rem;
        max-width: 400px;
        width: 90%;
        text-align: center;
    }
    
    .modal-title {
        color: var(--text-light);
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .modal-text {
        color: var(--text-gray);
        margin-bottom: 2rem;
        line-height: 1.5;
    }
    
    .modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }
    
    .btn-confirm {
        padding: 10px 20px;
        background: #EF4444;
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-confirm:hover {
        background: #DC2626;
    }
    
    .btn-cancel {
        padding: 10px 20px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        color: var(--text-light);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .events-grid {
            grid-template-columns: 1fr;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .event-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="events-management">
    <div class="container">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10B981; color: #10B981; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error" style="background: rgba(239, 68, 68, 0.1); border: 1px solid #EF4444; color: #EF4444; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                {{ session('error') }}
            </div>
        @endif
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Manage Events</h1>
            <div class="page-actions">
                <a href="{{ route('admin.events.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Create New Event
                </a>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filters-section">
            <form id="filterForm" class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">Search Events</label>
                    <input type="text" name="search" class="filter-control" placeholder="Search by name..." value="{{ request('search') }}">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Date Range</label>
                    <input type="date" name="date_from" class="filter-control" value="{{ request('date_from') }}">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">&nbsp;</label>
                    <input type="date" name="date_to" class="filter-control" value="{{ request('date_to') }}">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">&nbsp;</label>
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Events Grid -->
        <div class="events-grid" id="eventsGrid">
            @forelse($events as $event)
                <div class="event-card">
                    <div class="event-image">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-calendar-alt"></i>
                        @endif
                        <span class="event-status status-{{ $event->event_date > now() ? 'upcoming' : ($event->is_active ? 'active' : 'completed') }}">
                            {{ $event->event_date > now() ? 'Upcoming' : ($event->is_active ? 'Active' : 'Completed') }}
                        </span>
                    </div>
                    <div class="event-content">
                        <h3 class="event-title">{{ $event->title }}</h3>
                        <div class="event-date">
                            <i class="fas fa-calendar"></i>
                            {{ $event->event_date->format('M d, Y') }} • {{ $event->event_date->format('g:i A') }}
                        </div>
                        <p class="event-description">
                            {{ Str::limit($event->description, 120) }}
                        </p>
                        <div class="event-stats">
                            <div class="stat-item">
                                <p class="stat-value">{{ $event->registrations_count ?? 0 }}</p>
                                <p class="stat-label">Registered</p>
                            </div>
                            <div class="stat-item">
                                <p class="stat-value">{{ $event->max_participants ?? '∞' }}</p>
                                <p class="stat-label">Capacity</p>
                            </div>
                            <div class="stat-item">
                                <p class="stat-value">
                                    @if($event->max_participants)
                                        {{ round(($event->registrations_count ?? 0) / $event->max_participants * 100) }}%
                                    @else
                                        -
                                    @endif
                                </p>
                                <p class="stat-label">Filled</p>
                            </div>
                        </div>
                        <div class="event-actions">
                            <a href="{{ route('admin.events.show', $event->id) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </a>
                            <button class="btn-action btn-delete" onclick="confirmDelete('{{ $event->title }}', {{ $event->id }})">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Events Found</h3>
                    <p>No events match your current filters. Try adjusting your search criteria.</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($events->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Events pagination">
                    <div class="pagination-wrapper" style="background: rgba(255, 255, 255, 0.1); border-radius: 10px; padding: 15px;">
                        {{ $events->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </nav>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <h3 class="modal-title">Confirm Delete</h3>
        <p class="modal-text">Are you sure you want to delete "<span id="eventName"></span>"? This action cannot be undone.</p>
        <div class="modal-actions">
            <button class="btn-confirm" onclick="deleteEvent()">Delete</button>
            <button class="btn-cancel" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<!-- Hidden delete form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
let eventToDelete = null;
let eventIdToDelete = null;

function confirmDelete(eventName, eventId) {
    eventToDelete = eventName;
    eventIdToDelete = eventId;
    document.getElementById('eventName').textContent = eventName;
    document.getElementById('deleteModal').classList.add('show');
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('show');
    eventToDelete = null;
    eventIdToDelete = null;
}

function deleteEvent() {
    if (eventIdToDelete) {
        const form = document.getElementById('deleteForm');
        form.action = `{{ route('admin.events.index') }}/${eventIdToDelete}`;
        form.submit();
    }
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Filter form submission - submit to server
document.getElementById('filterForm').addEventListener('submit', function(e) {
    // Let the form submit normally to the server
    // The form will be processed by the controller
});

// Auto-submit filter form on input change
document.querySelectorAll('.filter-control').forEach(input => {
    input.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
</script>
@endsection