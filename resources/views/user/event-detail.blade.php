@extends('layouts.app')

@section('title', $event->title . ' - Epic Events')

@push('styles')
<style>
    .event-detail-container {
        padding: 2rem 0;
        min-height: calc(100vh - 80px);
    }
    
    .event-hero {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .event-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 20px 20px 0 0;
    }
    
    .event-image-placeholder {
        height: 400px;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px 20px 0 0;
    }
    
    .event-image-placeholder i {
        font-size: 5rem;
        color: rgba(255, 255, 255, 0.3);
    }
    
    .event-content {
        padding: 2rem;
    }
    
    .event-status {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }
    
    .status-upcoming {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    .status-completed {
        background: rgba(156, 163, 175, 0.2);
        color: #9ca3af;
        border: 1px solid rgba(156, 163, 175, 0.3);
    }
    
    .status-cancelled {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .event-title {
        font-family: 'Orbitron', monospace;
        font-size: 2.5rem;
        font-weight: 700;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    
    .event-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .meta-icon {
        width: 40px;
        height: 40px;
        background: var(--gradient-accent);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
    }
    
    .meta-content h6 {
        color: var(--text-light);
        font-size: 0.9rem;
        margin: 0;
        font-weight: 600;
    }
    
    .meta-content p {
        color: var(--text-gray);
        font-size: 0.8rem;
        margin: 0;
    }
    
    .event-description {
        color: var(--text-gray);
        line-height: 1.8;
        font-size: 1rem;
        margin-bottom: 2rem;
    }
    
    .registration-section {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
    }
    
    .registration-title {
        color: var(--text-light);
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .registration-info {
        color: var(--text-gray);
        margin-bottom: 1.5rem;
    }
    
    .btn-register {
        padding: 15px 30px;
        background: var(--gradient-accent);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }
    
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-register:disabled {
        background: rgba(156, 163, 175, 0.3);
        color: #9ca3af;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .btn-back {
        padding: 10px 20px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: var(--text-light);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }
    
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        text-decoration: none;
    }
    
    .whatsapp-share {
        margin-top: 1rem;
    }
    
    .btn-whatsapp {
        padding: 10px 20px;
        background: #25D366;
        border: none;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-whatsapp:hover {
        background: #128C7E;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }
    
    .participants-info {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .participants-bar {
        width: 100%;
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    
    .participants-fill {
        height: 100%;
        background: var(--gradient-accent);
        border-radius: 4px;
    }
    
    /* Registration Form Styles */
    .registration-form {
        margin-top: 1.5rem;
    }
    
    .form-section-title {
        color: var(--text-light);
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }
    
    .form-fields-section,
    .payment-section {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .form-label {
        color: var(--text-light);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .form-control,
    .form-select {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: var(--text-light);
        padding: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus,
    .form-select:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        color: var(--text-light);
    }
    
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .form-text {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.875rem;
    }
    
    .invalid-feedback {
        color: #ff6b6b;
        font-size: 0.875rem;
    }
    
    .is-invalid {
        border-color: #ff6b6b;
    }
    
    /* Payment Section Styles */
    .payment-info-card {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    
    .payment-amount {
        font-size: 1.25rem;
        color: var(--accent-color);
        margin-bottom: 1rem;
        text-align: center;
        padding: 0.75rem;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 8px;
    }
    
    .payment-instructions {
        color: var(--text-light);
    }
    
    .payment-instructions p {
        margin-bottom: 0.75rem;
        font-weight: 500;
    }
    
    .payment-instructions ol {
        margin-bottom: 0;
        padding-left: 1.25rem;
    }
    
    .payment-instructions li {
        margin-bottom: 0.5rem;
        line-height: 1.5;
    }
    
    .payment-instructions strong {
        color: var(--accent-color);
    }
    
    /* Button Styles */
    .btn-register.w-100 {
        margin-top: 1rem;
        padding: 1rem;
        font-size: 1.1rem;
        font-weight: 600;
    }
        transition: width 0.3s ease;
    }
    
    @media (max-width: 768px) {
        .event-title {
            font-size: 2rem;
        }
        
        .event-meta {
            grid-template-columns: 1fr;
        }
        
        .event-content {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container event-detail-container">
    <a href="{{ route('events') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i>
        Back to Events
    </a>
    
    <div class="event-hero">
        @if($event->images && count($event->images) > 0)
            <img src="{{ asset('storage/' . $event->images[0]) }}" alt="{{ $event->title }}" class="event-image">
        @elseif($event->image)
            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="event-image">
        @else
            <div class="event-image-placeholder">
                <i class="fas fa-calendar-alt"></i>
            </div>
        @endif
        
        <div class="event-content">
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
            
            <span class="event-status {{ $statusClass }}">
                {{ ucfirst($status) }}
            </span>
            
            <h1 class="event-title">{{ $event->title }}</h1>
            
            <div class="event-meta">
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="meta-content">
                        <h6>Event Date</h6>
                        <p>{{ $event->event_date ? $event->event_date->format('l, F j, Y') : 'To Be Announced' }}</p>
                    </div>
                </div>
                
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="meta-content">
                        <h6>Time</h6>
                        <p>{{ $event->event_date ? $event->event_date->format('g:i A') : 'TBA' }}</p>
                    </div>
                </div>
                
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="meta-content">
                        <h6>Location</h6>
                        <p>{{ $event->location }}</p>
                    </div>
                </div>
                
                <div class="meta-item">
                    <div class="meta-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="meta-content">
                        <h6>Price</h6>
                        <p>
                            @if($event->price == 0 || !$event->price)
                                Free
                            @else
                                Rp {{ number_format($event->price, 0, ',', '.') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="event-description">
                <h4 style="color: var(--text-light); margin-bottom: 1rem;">About This Event</h4>
                <p>{{ $event->description }}</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Additional event details can be added here -->
        </div>
      
    </div>
</div>
@endsection