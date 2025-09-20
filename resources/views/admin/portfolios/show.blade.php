@extends('layouts.admin')

@section('title', 'Detail Portfolio')

@section('content')
<div class="portfolio-show">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-eye"></i>
            Detail Portfolio
        </h1>
        <p class="page-description">{{ $portfolio->title }}</p>
        <div class="page-actions">
            <a href="{{ route('admin.portfolios.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
            <a href="{{ route('admin.portfolios.edit', $portfolio) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i>
                Edit
            </a>
            <form action="{{ route('admin.portfolios.destroy', $portfolio) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus portfolio ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="portfolio-content">
        <div class="portfolio-info">
            <div class="info-card">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Portfolio
                </h3>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label>Judul:</label>
                        <span>{{ $portfolio->title }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Status:</label>
                        <span class="badge badge-{{ $portfolio->status == 'published' ? 'success' : 'warning' }}">
                            {{ ucfirst($portfolio->status) }}
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <label>Event:</label>
                        @if($portfolio->event)
                            <div class="event-info">
                                <strong>{{ $portfolio->event->title }}</strong>
                                <small class="text-muted d-block">{{ $portfolio->event->event_date->format('d M Y') }}</small>
                                <small class="text-muted">{{ $portfolio->event->location }}</small>
                            </div>
                        @else
                            <span class="text-muted">Event tidak ditemukan</span>
                        @endif
                    </div>
                    
                    <div class="info-item">
                        <label>Jumlah Gambar:</label>
                        <span class="image-count">
                            <i class="fas fa-images"></i>
                            {{ $portfolio->image_count }}
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <label>Dibuat:</label>
                        <span>{{ $portfolio->created_at->format('d M Y H:i') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Diupdate:</label>
                        <span>{{ $portfolio->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
                
                @if($portfolio->description)
                    <div class="description-section">
                        <label>Deskripsi:</label>
                        <div class="description-content">
                            {{ $portfolio->description }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        @if($portfolio->images && $portfolio->image_count > 0)
            <div class="portfolio-gallery">
                <div class="gallery-card">
                    <h3 class="card-title">
                        <i class="fas fa-images"></i>
                        Galeri Portfolio ({{ $portfolio->image_count }} gambar)
                    </h3>
                    
                    <div class="gallery-grid">
                        @foreach((is_array($portfolio->images) ? $portfolio->images : json_decode($portfolio->images, true)) as $index => $image)
                            <div class="gallery-item" data-index="{{ $index }}">
                                <img src="{{ Storage::disk('public_uploads')->url($image) }}" alt="Portfolio Image {{ $index + 1 }}" class="gallery-image" onclick="openLightbox({{ $index }})">
                                <div class="image-overlay">
                                    <button class="btn btn-sm btn-primary" onclick="openLightbox({{ $index }})">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <a href="{{ Storage::disk('public_uploads')->url($image) }}" download class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="no-images">
                <div class="empty-state">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada gambar</h5>
                    <p class="text-muted">Portfolio ini belum memiliki gambar. Klik edit untuk menambahkan gambar.</p>
                    <a href="{{ route('admin.portfolios.edit', $portfolio) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Tambah Gambar
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Lightbox Modal -->
@if($portfolio->images && $portfolio->image_count > 0)
<div id="lightboxModal" class="lightbox-modal">
    <div class="lightbox-content">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <button class="lightbox-nav lightbox-prev" onclick="changeLightboxImage(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="lightbox-nav lightbox-next" onclick="changeLightboxImage(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
        <img id="lightboxImage" src="" alt="Portfolio Image">
        <div class="lightbox-info">
            <span id="lightboxCounter"></span>
            <a id="lightboxDownload" href="" download class="btn btn-sm btn-success">
                <i class="fas fa-download"></i>
                Download
            </a>
        </div>
    </div>
</div>
@endif

<style>
.portfolio-show {
    padding: 20px;
}

.portfolio-content {
    display: grid;
    gap: 30px;
}

.info-card,
.gallery-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    padding: 25px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.card-title {
    color: #fff;
    font-size: 1.2rem;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item label {
    font-weight: 600;
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

.info-item span {
    color: #fff;
    font-size: 1rem;
}

.event-info strong {
    color: #4fc3f7;
}

.image-count {
    color: #81c784;
    font-weight: 500;
}

.badge-success {
    background: linear-gradient(135deg, #4caf50, #45a049);
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
}

.badge-warning {
    background: linear-gradient(135deg, #ff9800, #f57c00);
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
}

.description-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.description-section label {
    font-weight: 600;
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin-bottom: 10px;
    display: block;
}

.description-content {
    color: #fff;
    line-height: 1.6;
    background: rgba(255, 255, 255, 0.05);
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #4fc3f7;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.gallery-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.gallery-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: all 0.3s ease;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    transition: all 0.3s ease;
}

.gallery-item:hover .image-overlay {
    opacity: 1;
}

.no-images {
    text-align: center;
    padding: 60px 20px;
}

.empty-state {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    padding: 40px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Lightbox Styles */
.lightbox-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(5px);
}

.lightbox-content {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-close {
    position: absolute;
    top: 20px;
    right: 30px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10001;
    transition: all 0.3s ease;
}

.lightbox-close:hover {
    color: #ff5252;
    transform: scale(1.1);
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border: none;
    padding: 15px 20px;
    font-size: 20px;
    cursor: pointer;
    border-radius: 50%;
    transition: all 0.3s ease;
    z-index: 10001;
}

.lightbox-nav:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-50%) scale(1.1);
}

.lightbox-prev {
    left: 30px;
}

.lightbox-next {
    right: 30px;
}

#lightboxImage {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

.lightbox-info {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 20px;
    background: rgba(0, 0, 0, 0.7);
    padding: 10px 20px;
    border-radius: 25px;
    backdrop-filter: blur(10px);
}

#lightboxCounter {
    color: #fff;
    font-weight: 500;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
    
    .gallery-image {
        height: 150px;
    }
    
    .lightbox-nav {
        padding: 10px 15px;
        font-size: 16px;
    }
    
    .lightbox-prev {
        left: 15px;
    }
    
    .lightbox-next {
        right: 15px;
    }
    
    .lightbox-close {
        top: 15px;
        right: 20px;
        font-size: 30px;
    }
}
</style>

@if($portfolio->images && $portfolio->image_count > 0)
<script>
let currentImageIndex = 0;
const images = @json($portfolio->images);

function openLightbox(index) {
    currentImageIndex = index;
    updateLightboxImage();
    document.getElementById('lightboxModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightboxModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function changeLightboxImage(direction) {
    currentImageIndex += direction;
    if (currentImageIndex >= images.length) {
        currentImageIndex = 0;
    } else if (currentImageIndex < 0) {
        currentImageIndex = images.length - 1;
    }
    updateLightboxImage();
}

function updateLightboxImage() {
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCounter = document.getElementById('lightboxCounter');
    const lightboxDownload = document.getElementById('lightboxDownload');
    
    const imageUrl = '{{ Storage::disk('public_uploads')->url("") }}' + images[currentImageIndex];
    lightboxImage.src = imageUrl;
    lightboxCounter.textContent = `${currentImageIndex + 1} / ${images.length}`;
    lightboxDownload.href = imageUrl;
}

// Close lightbox when clicking outside the image
document.getElementById('lightboxModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (document.getElementById('lightboxModal').style.display === 'block') {
        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            changeLightboxImage(-1);
        } else if (e.key === 'ArrowRight') {
            changeLightboxImage(1);
        }
    }
});
</script>
@endif
@endsection