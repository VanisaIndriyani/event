@extends('layouts.app')

@section('title', 'Portfolio - Epic Events')

@push('styles')
<style>
    .portfolio-container {
        padding: 3rem 0;
        min-height: calc(100vh - 80px);
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.95) 100%);
    }
    
    .page-header {
        text-align: center;
        margin-bottom: 4rem;
        padding: 2rem 0;
    }
    
    .page-title {
        font-family: 'Orbitron', monospace;
        font-size: 3.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6, #06b6d4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
        text-shadow: 0 0 30px rgba(59, 130, 246, 0.3);
    }
    
    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.3rem;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
        font-weight: 300;
    }
    
    .filters-section {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 3rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }
    
    .filter-tabs {
        display: flex;
        justify-content: center;
        gap: 1.2rem;
        flex-wrap: wrap;
    }
    
    .filter-tab {
        padding: 1rem 2rem;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 30px;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .filter-tab::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.5s;
    }
    
    .filter-tab:hover::before {
        left: 100%;
    }
    
    .filter-tab:hover,
    .filter-tab.active {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        border-color: transparent;
        color: white;
        text-decoration: none;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
    }
    
    .portfolio-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        margin-bottom: 4rem;
        padding: 0 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    @media (min-width: 768px) {
        .portfolio-grid {
            padding: 0;
        }
    }
    
    .portfolio-item {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        display: flex;
        flex-direction: row;
        min-height: 280px;
        position: relative;
        max-width: 100%;
    }
    
    .portfolio-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 1;
    }
    
    .portfolio-item:hover::before {
        opacity: 1;
    }
    
    .portfolio-item:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
        border-color: rgba(59, 130, 246, 0.3);
    }
    
    .portfolio-image {
        width: 40%;
        min-height: 280px;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        z-index: 2;
    }
    
    @media (max-width: 768px) {
        .portfolio-item {
            flex-direction: column;
            min-height: auto;
        }
        
        .portfolio-image {
            width: 100%;
            height: 250px;
        }
    }
    
    .portfolio-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .portfolio-item:hover .portfolio-image img {
        transform: scale(1.15);
    }
    
    .portfolio-image .placeholder {
        font-size: 4.5rem;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
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
        padding: 2.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        z-index: 2;
        background: rgba(255, 255, 255, 0.02);
    }
    
    .portfolio-header {
        flex: 1;
        margin-bottom: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .portfolio-content {
            padding: 1.5rem;
        }
    }
    
    .portfolio-category {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa;
        border-radius: 20px;
        font-size: 0.85rem;
        border: 1px solid rgba(59, 130, 246, 0.3);
        backdrop-filter: blur(10px);
        font-weight: 500;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .portfolio-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 1rem;
        line-height: 1.4;
        font-family: 'Orbitron', monospace;
    }
    
    .portfolio-description {
        color: rgba(255, 255, 255, 0.75);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        text-align: justify;
    }
    
    .portfolio-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7);
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .portfolio-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }
    
    .portfolio-photos {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }
    
    .portfolio-date i,
    .portfolio-photos i {
        color: rgba(59, 130, 246, 0.8);
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
        .portfolio-container {
            padding: 2rem 0;
        }
        
        .page-header {
            margin-bottom: 3rem;
            padding: 1rem 0;
        }
        
        .page-title {
            font-size: 2.8rem;
        }
        
        .page-subtitle {
            font-size: 1.1rem;
            padding: 0 1rem;
        }
        
        .filters-section {
            margin: 0 1rem 2rem 1rem;
            padding: 1.5rem;
        }
        
        .portfolio-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 0 1rem;
        }
        
        .filter-tabs {
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            gap: 1rem;
        }
        
        .filter-tab {
            padding: 0.8rem 1.5rem;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        
        .portfolio-content {
            padding: 1.5rem;
        }
        
        .lightbox-nav {
            display: none;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 480px) {
        .page-title {
            font-size: 2.2rem;
        }
        
        .portfolio-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .portfolio-item {
            min-height: auto;
        }
        
        .portfolio-image {
            height: 180px;
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
        
      
        
      
     <!-- Portfolio Grid -->
<div class="portfolio-grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="portfolioGrid">
    @if($pastEvents->count() > 0)
        @foreach($pastEvents as $index => $portfolio)
            <div 
                class="portfolio-item" 
                data-category="{{ strtolower($portfolio->status ?? 'general') }}" 
                onclick="openLightbox({{ $index }}, 0)"
            >
                <!-- Image Section -->
                <div class="portfolio-image">
                    @if($portfolio->first_image)
                        <img src="{{ Storage::disk('public_uploads')->url($portfolio->first_image) }}" alt="{{ $portfolio->title }}">
                    @elseif($portfolio->event->image)
                        <img src="{{ Storage::disk('public_uploads')->url($portfolio->event->image) }}" alt="{{ $portfolio->title }}">
                    @else
                        <div class="placeholder">
                            @if(stripos($portfolio->title, 'music') !== false)
                                <i class="fas fa-music"></i>
                            @elseif(stripos($portfolio->title, 'tech') !== false || stripos($portfolio->title, 'technology') !== false)
                                <i class="fas fa-laptop-code"></i>
                            @elseif(stripos($portfolio->title, 'sport') !== false || stripos($portfolio->title, 'game') !== false || stripos($portfolio->title, 'tournament') !== false)
                                <i class="fas fa-running"></i>
                            @elseif(stripos($portfolio->title, 'art') !== false || stripos($portfolio->title, 'photo') !== false || stripos($portfolio->title, 'exhibition') !== false)
                                <i class="fas fa-palette"></i>
                            @elseif(stripos($portfolio->title, 'business') !== false || stripos($portfolio->title, 'networking') !== false || stripos($portfolio->title, 'startup') !== false)
                                <i class="fas fa-briefcase"></i>
                            @else
                                <i class="fas fa-calendar"></i>
                            @endif
                        </div>
                    @endif

                    <!-- Overlay -->
                    <div class="portfolio-overlay">
                        <div class="overlay-content">
                            <i class="fas fa-search-plus overlay-icon"></i>
                            <div class="overlay-text">View Gallery</div>
                        </div>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="portfolio-content">
                    <div class="portfolio-header">
                        <div class="portfolio-category">
                            Epic Events #{{ $portfolio->id }}
                        </div>
                        <h3 class="portfolio-title">{{ $portfolio->title }}</h3>
                        <p class="portfolio-description">
                            {{ $portfolio->description }}
                        </p>
                    </div>
                    
                    <div class="portfolio-meta">
                        <div class="portfolio-date">
                            <i class="fas fa-calendar"></i>
                            {{ $portfolio->event->event_date->format('F j, Y') }}
                        </div>
                        <div class="portfolio-photos">
                            <i class="fas fa-images"></i>
                            {{ $portfolio->image_count }} {{ $portfolio->image_count == 1 ? 'photo' : 'photos' }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-span-full text-center py-10">
            <div class="flex flex-col items-center justify-center text-gray-400">
                <i class="fas fa-images fa-4x mb-3"></i>
                <h4 class="text-lg font-semibold">No Portfolio Available</h4>
                <p class="text-sm">There are no past events to display in the portfolio yet.</p>
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
let currentPortfolioIndex = 0;
const portfolioData = [
    @foreach($pastEvents as $portfolio)
    {
        title: '{{ $portfolio->title }}',
        category: '{{ ucfirst($portfolio->status) }}',
        date: '{{ $portfolio->event->event_date->format("F j, Y") }}',
        photos: {{ $portfolio->image_count ?? 0 }},
        images: [
            @if($portfolio->images && is_array($portfolio->images))
                @foreach($portfolio->images as $image)
                    '{{ Storage::disk("public_uploads")->url($image) }}',
                @endforeach
            @elseif($portfolio->first_image)
                '{{ Storage::disk("public_uploads")->url($portfolio->first_image) }}',
            @elseif($portfolio->event->image)
                '{{ Storage::disk("public_uploads")->url($portfolio->event->image) }}',
            @endif
        ],
        description: '{{ Str::limit($portfolio->description, 120) }}'
    },
    @endforeach
];

function openLightbox(portfolioIndex, imageIndex = 0) {
    currentPortfolioIndex = portfolioIndex;
    currentImageIndex = imageIndex;
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxInfo = document.getElementById('lightboxInfo');
    
    const data = portfolioData[portfolioIndex];
    
    // Use actual portfolio images or placeholder
    if (data.images && data.images.length > 0 && data.images[imageIndex]) {
        lightboxImage.src = data.images[imageIndex];
    } else {
        // Use placeholder SVG if no image
        lightboxImage.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjYwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48bGluZWFyR3JhZGllbnQgaWQ9ImEiIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPjxzdG9wIG9mZnNldD0iMCUiIHN0b3AtY29sb3I9IiMzYjgyZjYiLz48c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiM4YjVjZjYiLz48L2xpbmVhckdyYWRpZW50PjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2EpIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSI0OCIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5FcGljIEV2ZW50czwvdGV4dD48L3N2Zz4=';
    }
    
    const currentImageNum = imageIndex + 1;
    const totalImages = data.images ? data.images.length : 0;
    
    lightboxInfo.innerHTML = `
        <h4>${data.title}</h4>
        <p>${data.category} • ${data.date} • ${data.photos} ${data.photos == 1 ? 'photo' : 'photos'}</p>
        <p class="text-sm text-gray-300">Image ${currentImageNum} of ${totalImages}</p>
        <p class="mt-2">${data.description}</p>
    `;
    
    lightbox.classList.add('active');
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
}

function nextImage() {
    const currentPortfolio = portfolioData[currentPortfolioIndex];
    if (currentPortfolio && currentPortfolio.images && currentPortfolio.images.length > 0) {
        currentImageIndex = (currentImageIndex + 1) % currentPortfolio.images.length;
        openLightbox(currentPortfolioIndex, currentImageIndex);
    }
}

function prevImage() {
    const currentPortfolio = portfolioData[currentPortfolioIndex];
    if (currentPortfolio && currentPortfolio.images && currentPortfolio.images.length > 0) {
        currentImageIndex = (currentImageIndex - 1 + currentPortfolio.images.length) % currentPortfolio.images.length;
        openLightbox(currentPortfolioIndex, currentImageIndex);
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