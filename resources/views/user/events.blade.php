@extends('layouts.app')

@section('title', 'Events - Epic Events')

@push('styles')
<style>
    .events-container {
        padding: 2rem 0;
        min-height: calc(100vh - 80px);
    }
    
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .page-title {
        font-family: 'Orbitron', monospace;
        font-size: 3rem;
        font-weight: 700;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }
    
    .page-subtitle {
        color: var(--text-gray);
        font-size: 1.2rem;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .filters-section {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
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
    }
    
    .filter-label {
        color: var(--text-light);
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .filter-control {
        padding: 10px 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: var(--text-light);
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .filter-control:focus {
        outline: none;
        border-color: var(--accent-blue);
        background: rgba(255, 255, 255, 0.1);
    }
    
    .filter-control option {
        background: #1A2332;
        color: var(--text-light);
        padding: 8px;
    }
    
    .filter-control option:hover {
        background: #2563EB;
    }
    
    .btn-filter {
        padding: 10px 20px;
        background: var(--gradient-accent);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
    }
    
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .event-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .event-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }
    
    .event-image {
        width: 100%;
        height: 200px;
        background: var(--gradient-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .event-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .event-image .placeholder {
        font-size: 4rem;
        color: rgba(255, 255, 255, 0.8);
    }
    
    .event-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .status-upcoming {
        background: rgba(16, 185, 129, 0.2);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .status-ongoing {
        background: rgba(245, 158, 11, 0.2);
        color: #F59E0B;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    .status-ended {
        background: rgba(107, 114, 128, 0.2);
        color: #6B7280;
        border: 1px solid rgba(107, 114, 128, 0.3);
    }
    
    .event-content {
        padding: 1.5rem;
    }
    
    .event-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--accent-blue);
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .event-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    
    .event-description {
        color: var(--text-gray);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .event-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 0.8rem;
    }
    
    .event-location {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        color: var(--text-gray);
    }
    
    .event-price {
        color: var(--accent-blue);
        font-weight: 600;
    }
    
    .event-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-primary {
        flex: 1;
        padding: 10px 16px;
        background: var(--gradient-accent);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-secondary {
        padding: 10px 16px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: var(--text-light);
        font-weight: 500;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        text-decoration: none;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-gray);
    }
    
    .empty-state i {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: var(--text-light);
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 2.5rem;
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
<div class="events-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Epic Events</h1>
            <p class="page-subtitle">Discover extraordinary experiences that will create unforgettable memories</p>
        </div>
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 15px; color: #22c55e;">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 15px; color: #ef4444;">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
        @endif
        
        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('events') }}">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Status</label>
                        <select name="category" class="filter-control">
                            <option value="">All Events</option>
                            <option value="upcoming" {{ request('category') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ request('category') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ request('category') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="music" {{ request('category') == 'music' ? 'selected' : '' }}>Music Events</option>
                            <option value="technology" {{ request('category') == 'technology' ? 'selected' : '' }}>Technology Events</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Date</label>
                        <input type="date" name="date" class="filter-control" value="{{ request('date') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Location</label>
                        <input type="text" name="location" class="filter-control" placeholder="Enter location" value="{{ request('location') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Price Range</label>
                        <select name="price" class="filter-control">
                            <option value="">Any Price</option>
                            <option value="free" {{ request('price') == 'free' ? 'selected' : '' }}>Free</option>
                            <option value="0-100000" {{ request('price') == '0-100000' ? 'selected' : '' }}>Under Rp 100K</option>
                            <option value="100000-500000" {{ request('price') == '100000-500000' ? 'selected' : '' }}>Rp 100K - 500K</option>
                            <option value="500000+" {{ request('price') == '500000+' ? 'selected' : '' }}>Above Rp 500K</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Events Grid -->
        @if(isset($events) && count($events) > 0)
            <div class="row g-4">
                @foreach($events as $event)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 event-card border-0">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="card-img-top event-image">
                            @else
                                <div class="card-img-top event-image d-flex align-items-center justify-content-center">
                                    <i class="fas fa-calendar-alt placeholder"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 end-0 m-3">
                                @php
                                    $status = 'upcoming';
                                    $statusClass = 'status-upcoming';
                                    if (!$event->is_active) {
                                        $status = 'cancelled';
                                        $statusClass = 'status-cancelled';
                                    } elseif ($event->event_date < now()) {
                                        $status = 'completed';
                                        $statusClass = 'status-completed';
                                    }
                                @endphp
                                <span class="badge event-status {{ $statusClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <div class="event-date mb-2">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $event->event_date ? $event->event_date->format('M d, Y') : 'TBA' }}
                                </div>
                                
                                <h5 class="card-title event-title">{{ $event->title }}</h5>
                                
                                <p class="card-text event-description flex-grow-1">
                                    {{ Str::limit($event->description, 120) }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-muted event-location">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $event->location }}
                                    </small>
                                    @if($event->price == 0 || !$event->price)
                                        <span class="badge bg-success fs-6">Free</span>
                                    @else
                                        <span class="badge bg-primary fs-6">
                                            Rp {{ number_format($event->price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="d-flex gap-2 mt-auto">
                                    <a href="{{ route('event.detail', $event->id ?? 1) }}" class="btn btn-primary flex-fill">
                                        <i class="fas fa-info-circle me-1"></i>Details
                                    </a>
                                    @auth
                                        <a href="{{ route('event.registration.form', $event->id ?? 1) }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-ticket-alt me-1"></i>Daftar
                                        </a>
                                    @else
                                        <button type="button" class="btn btn-outline-primary w-100" onclick="showLoginModal()">
                                            <i class="fas fa-ticket-alt me-1"></i>Daftar
                                        </button>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Events Available</h4>
                    <p class="text-muted">There are currently no events to display. Please check back later.</p>
                </div>
            </div>
        @endif
        
        <!-- Pagination -->
        @if(isset($events) && method_exists($events, 'links'))
            <div class="d-flex justify-content-center mt-5">
                <nav aria-label="Events pagination">
                    <div class="pagination-wrapper" style="background: rgba(255, 255, 255, 0.1); border-radius: 10px; padding: 15px;">
                        {{ $events->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </nav>
            </div>
        @endif
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--glass-bg); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1);">
            <div class="modal-header border-0">
                <h5 class="modal-title text-light" id="loginModalLabel">
                    <i class="fas fa-sign-in-alt me-2"></i>Login Required
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fas fa-lock fa-3x text-primary mb-3"></i>
                    <h6 class="text-light mb-3">You need to login to register for events</h6>
                    <p class="text-muted">Please login to your account or create a new account to continue with event registration.</p>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showLoginModal() {
    var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.show();
}
</script>
@endpush

@endsection