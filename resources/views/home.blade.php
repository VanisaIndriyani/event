@extends('layouts.app')

@section('title', 'lark fest - Where extraordinary moments become unforgettable memories')

@push('styles')
<style>
    .hero-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    
    .hero-content {
        z-index: 2;
        position: relative;
    }
    
    .hero-title {
        font-family: 'Orbitron', monospace;
        font-size: 4rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 50%, #8B5CF6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        color: var(--text-gray);
        margin-bottom: 2rem;
        max-width: 600px;
    }
    
    .floating-elements {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
    }
    
    .floating-element {
        position: absolute;
        opacity: 0.1;
        animation: float 6s ease-in-out infinite;
    }
    
    .floating-element:nth-child(1) {
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .floating-element:nth-child(2) {
        top: 60%;
        right: 15%;
        animation-delay: 2s;
    }
    
    .floating-element:nth-child(3) {
        bottom: 30%;
        left: 20%;
        animation-delay: 4s;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .section-title {
        font-family: 'Orbitron', monospace;
        font-size: 2.5rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 3rem;
        background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .event-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .event-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(59, 130, 246, 0.2);
        border-color: rgba(59, 130, 246, 0.3);
    }
    
    .event-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #1A2332 0%, #2563EB 100%);
    }
    
    .event-content {
        padding: 1.5rem;
    }
    
    .event-date {
        color: var(--cyan);
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .event-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--text-light);
    }
    
    .event-description {
        color: var(--text-gray);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }
    
    .stats-section {
        background: rgba(15, 23, 42, 0.9);
        padding: 4rem 0;
        margin: 4rem 0;
    }
    
    .stat-card {
        text-align: center;
        padding: 2rem;
    }
    
    .stat-number {
        font-family: 'Orbitron', monospace;
        font-size: 3rem;
        font-weight: 800;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: block;
    }
    
    .stat-label {
        color: var(--text-gray);
        font-size: 1.1rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="floating-elements">
        <div class="floating-element">
            <i class="fas fa-star" style="font-size: 3rem; color: var(--accent-blue);"></i>
        </div>
        <div class="floating-element">
            <i class="fas fa-heart" style="font-size: 2.5rem; color: var(--purple);"></i>
        </div>
        <div class="floating-element">
            <i class="fas fa-music" style="font-size: 2rem; color: var(--cyan);"></i>
        </div>
    </div>
    
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-8 mx-auto text-center">
                <div class="hero-content">
                    <h1 class="hero-title display-1 fw-bold">lark fest</h1>
                    <p class="hero-subtitle lead fs-4 mb-4">
                        Where extraordinary moments become unforgettable memories ✨
                    </p>
                    <p class="mb-4" style="color: var(--text-gray); font-size: 1.1rem;">
                        Welcome to our universe of lark fest experiences! Here, every event is crafted with passion, 
                        creativity, and an obsession for perfection. From intimate gatherings to grand 
                        celebrations, we transform ideas into immersive experiences that resonate long after the 
                        last guest leaves.
                    </p>
                    <div class="hero-actions d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('events') }}" class="btn btn-primary btn-lg px-4 py-3">
                            <i class="fas fa-calendar-alt me-2"></i>Explore Events
                        </a>
                        <a href="{{ route('portfolio') }}" class="btn btn-outline-light btn-lg px-4 py-3">
                            <i class="fas fa-images me-2"></i>View Portfolio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Featured Events -->
@if($featuredEvents->count() > 0)
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Upcoming lark fest</h2>
        <div class="row g-4">
            @foreach($featuredEvents as $event)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 event-card border-0">
                    @if($event->images && count($event->images) > 0)
                        <img src="{{ asset('storage/' . $event->images[0]) }}" alt="{{ $event->title }}" class="card-img-top event-image">
                    @elseif($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="card-img-top event-image">
                    @else
                        <div class="card-img-top event-image d-flex align-items-center justify-content-center">
                            <i class="fas fa-calendar-alt" style="font-size: 3rem; color: var(--text-gray);"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <div class="event-date mb-2">
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                        </div>
                        <h5 class="card-title event-title">{{ $event->title }}</h5>
                        <p class="card-text event-description flex-grow-1">
                            {{ Str::limit($event->description, 100) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $event->location }}
                            </small>
                            @if($event->price)
                                <span class="badge bg-primary fs-6">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="badge bg-success fs-6">FREE</span>
                            @endif
                        </div>
                        <a href="{{ route('event.detail', $event->id) }}" class="btn btn-primary mt-auto">
                            <i class="fas fa-info-circle me-2"></i>View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('events') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>View All Events
            </a>
        </div>
    </div>
</section>
@endif

<!-- Featured Merchandise -->
@if($featuredMerchandise->count() > 0)
<section class="py-5">
    <div class="container">
        <h2 class="section-title">lark fest</h2>
        <div class="row g-4">
            @foreach($featuredMerchandise as $item)
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 event-card border-0">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="card-img-top event-image">
                    @else
                        <div class="card-img-top event-image d-flex align-items-center justify-content-center">
                            <i class="fas fa-shopping-bag" style="font-size: 3rem; color: var(--text-gray);"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title event-title">{{ $item->name }}</h5>
                        <p class="card-text event-description flex-grow-1">
                            {{ Str::limit($item->description, 80) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary fs-6">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </span>
                            <small class="text-muted">
                                Stock: {{ $item->stock }}
                            </small>
                        </div>
                        <a href="https://wa.me/6281234567890?text=Halo, saya tertarik dengan {{ $item->name }}" 
                           target="_blank" class="btn btn-success mt-auto">
                            <i class="fab fa-whatsapp me-2"></i>Order via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('merchandise') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>View All Merchandise
            </a>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-5 text-white" style="background: var(--gradient-primary);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-4">Ready to Create lark festMemories?</h2>
                <p class="lead mb-4" style="color: var(--text-gray);">
                    Join thousands of satisfied clients who have experienced the magic of our events. 
                    Let's create something extraordinary together!
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Join Us Now
                        </a>
                    @endguest
                    <a href="{{ route('collab') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-handshake me-2"></i>Let's Collaborate
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection