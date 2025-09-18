@extends('layouts.app')

@section('title', 'Portfolio - Epic Events')

@push('styles')
<style>
    .portfolio-container {
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
    
    .filter-tabs {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .filter-tab {
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        color: var(--text-light);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .filter-tab:hover,
    .filter-tab.active {
        background: var(--gradient-accent);
        border-color: transparent;
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
    }
    
    .portfolio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .portfolio-item {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .portfolio-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }
    
    .portfolio-image {
        width: 100%;
        height: 250px;
        background: var(--gradient-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .portfolio-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .portfolio-item:hover .portfolio-image img {
        transform: scale(1.1);
    }
    
    .portfolio-image .placeholder {
        font-size: 4rem;
        color: rgba(255, 255, 255, 0.8);
    }
    
    .portfolio-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .portfolio-item:hover .portfolio-overlay {
        opacity: 1;
    }
    
    .overlay-content {
        text-align: center;
        color: white;
    }
    
    .overlay-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .overlay-text {
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .portfolio-content {
        padding: 1.5rem;
    }
    
    .portfolio-category {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: rgba(59, 130, 246, 0.2);
        color: var(--accent-blue);
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .portfolio-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    
    .portfolio-description {
        color: var(--text-gray);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }
    
    .portfolio-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: var(--text-gray);
    }
    
    .portfolio-date {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .portfolio-photos {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    
    .lightbox.active {
        display: flex;
    }
    
    .lightbox-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
    }
    
    .lightbox-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .lightbox-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    
    .lightbox-close:hover {
        color: var(--accent-blue);
    }
    
    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 2rem;
        cursor: pointer;
        transition: color 0.3s ease;
        user-select: none;
    }
    
    .lightbox-nav:hover {
        color: var(--accent-blue);
    }
    
    .lightbox-prev {
        left: -60px;
    }
    
    .lightbox-next {
        right: -60px;
    }
    
    .lightbox-info {
        position: absolute;
        bottom: -60px;
        left: 0;
        right: 0;
        text-align: center;
        color: white;
    }
    
    .stats-section {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 3rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: var(--text-gray);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 2.5rem;
        }
        
        .portfolio-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-tabs {
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }
        
        .lightbox-nav {
            display: none;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="portfolio-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Epic Portfolio</h1>
            <p class="page-subtitle">Relive the magic through our collection of epic moments and memories</p>
        </div>
        
      
        
        <!-- Filters -->
        <div class="filters-section">
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">
                    <i class="fas fa-th me-2"></i>All Events
                </button>
                <button class="filter-tab" data-filter="music">
                    <i class="fas fa-music me-2"></i>Music
                </button>
                <button class="filter-tab" data-filter="sports">
                    <i class="fas fa-running me-2"></i>Sports
                </button>
                <button class="filter-tab" data-filter="technology">
                    <i class="fas fa-laptop-code me-2"></i>Technology
                </button>
                <button class="filter-tab" data-filter="art">
                    <i class="fas fa-palette me-2"></i>Art & Culture
                </button>
                <button class="filter-tab" data-filter="business">
                    <i class="fas fa-briefcase me-2"></i>Business
                </button>
            </div>
        </div>
        
        <!-- Portfolio Grid -->
        <div class="portfolio-grid" id="portfolioGrid">
            @if($pastEvents->count() > 0)
                @foreach($pastEvents as $index => $event)
                <div class="portfolio-item" data-category="{{ strtolower($event->category) }}" onclick="openLightbox({{ json_encode($index) }})">
                    <div class="portfolio-image">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                        @else
                            <div class="placeholder">
                                @switch($event->category)
                                    @case('Music')
                                        <i class="fas fa-music"></i>
                                        @break
                                    @case('Technology')
                                        <i class="fas fa-laptop-code"></i>
                                        @break
                                    @case('Sports')
                                        <i class="fas fa-running"></i>
                                        @break
                                    @case('Art')
                                        <i class="fas fa-palette"></i>
                                        @break
                                    @case('Business')
                                        <i class="fas fa-briefcase"></i>
                                        @break
                                    @default
                                        <i class="fas fa-calendar"></i>
                                @endswitch
                            </div>
                        @endif
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <div class="overlay-icon">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                                <div class="overlay-text">View Gallery</div>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-content">
                        <div class="portfolio-category">{{ $event->category }}</div>
                        <h3 class="portfolio-title">{{ $event->title }}</h3>
                        <p class="portfolio-description">
                            {{ Str::limit($event->description, 120) }}
                        </p>
                        <div class="portfolio-meta">
                            <div class="portfolio-date">
                                <i class="fas fa-calendar"></i>
                                {{ $event->event_date->format('F j, Y') }}
                            </div>
                            <div class="portfolio-photos">
                                <i class="fas fa-users"></i>
                                {{ $event->max_participants }} participants
                            </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-images fa-4x text-white mb-3"></i>
                        <h4 class="text-white">No Portfolio Available</h4>
                        <p class="text-white">There are no past events to display in the portfolio yet.</p>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Pagination -->
        @if($pastEvents->hasPages())
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Portfolio pagination">
                <div class="pagination-wrapper">
                    {{ $pastEvents->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <div class="lightbox-content">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <span class="lightbox-nav lightbox-prev" onclick="prevImage()">&lt;</span>
        <span class="lightbox-nav lightbox-next" onclick="nextImage()">&gt;</span>
        <img class="lightbox-image" id="lightboxImage" src="" alt="">
        <div class="lightbox-info" id="lightboxInfo"></div>
    </div>
</div>

<script>
// Portfolio filtering
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const portfolioItems = document.querySelectorAll('.portfolio-item');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            filterTabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            portfolioItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

// Lightbox functionality
let currentImageIndex = 0;
const portfolioData = [
    @foreach($pastEvents as $event)
    {
        title: '{{ $event->title }}',
        category: '{{ $event->category }}',
        date: '{{ $event->event_date->format("F j, Y") }}',
        participants: {{ $event->max_participants ?? 0 }},
        image: '{{ $event->image ? asset("storage/" . $event->image) : "" }}',
        description: '{{ Str::limit($event->description, 120) }}'
    },
    @endforeach
];

function openLightbox(index) {
    currentImageIndex = index;
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxInfo = document.getElementById('lightboxInfo');
    
    const data = portfolioData[index];
    
    // Use actual event image or placeholder
    if (data.image) {
        lightboxImage.src = data.image;
    } else {
        // Use placeholder SVG if no image
        lightboxImage.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjYwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48bGluZWFyR3JhZGllbnQgaWQ9ImEiIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPjxzdG9wIG9mZnNldD0iMCUiIHN0b3AtY29sb3I9IiMzYjgyZjYiLz48c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiM4YjVjZjYiLz48L2xpbmVhckdyYWRpZW50PjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2EpIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSI0OCIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FcGljIEV2ZW50czwvdGV4dD48L3N2Zz4=';
    }
    
    lightboxInfo.innerHTML = `
        <h4>${data.title}</h4>
        <p>${data.category} • ${data.date} • ${data.participants} participants</p>
        <p class="mt-2">${data.description}</p>
    `;
    
    lightbox.classList.add('active');
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
}

function nextImage() {
    if (portfolioData.length > 0) {
        currentImageIndex = (currentImageIndex + 1) % portfolioData.length;
        openLightbox(currentImageIndex);
    }
}

function prevImage() {
    if (portfolioData.length > 0) {
        currentImageIndex = (currentImageIndex - 1 + portfolioData.length) % portfolioData.length;
        openLightbox(currentImageIndex);
    }
}

// Close lightbox when clicking outside the image
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const lightbox = document.getElementById('lightbox');
    if (lightbox.classList.contains('active')) {
        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            prevImage();
        } else if (e.key === 'ArrowRight') {
            nextImage();
        }
    }
});
</script>
@endsection