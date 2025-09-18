@extends('layouts.app')

@section('title', 'Merchandise - Epic Events')

@push('styles')
<style>
    .merchandise-container {
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
        color: #94A3B8 !important;
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
        color: #F8FAFC !important;
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .filter-control {
        padding: 10px 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: #F8FAFC !important;
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
    
    .merchandise-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .merchandise-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .merchandise-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }
    
    .merchandise-image {
        width: 100%;
        height: 250px;
        background: var(--gradient-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .merchandise-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .merchandise-image .placeholder {
        font-size: 4rem;
        color: rgba(255, 255, 255, 0.8);
    }
    
    .stock-badge {
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
    
    .stock-available {
        background: rgba(16, 185, 129, 0.2);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .stock-low {
        background: rgba(245, 158, 11, 0.2);
        color: #F59E0B;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    .stock-out {
        background: rgba(239, 68, 68, 0.2);
        color: #EF4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .merchandise-content {
        padding: 1.5rem;
    }
    
    .merchandise-category {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: rgba(59, 130, 246, 0.2);
        color: #2563EB !important;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .merchandise-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #F8FAFC !important;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    
    .merchandise-description {
        color: #94A3B8 !important;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .merchandise-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .merchandise-price {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2563EB !important;
    }
    
    .merchandise-stock {
        color: #94A3B8 !important;
        font-size: 0.8rem;
    }
    
    .size-options {
        margin-bottom: 1rem;
    }
    
    .size-label {
        color: var(--text-light);
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .size-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .size-btn {
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        color: var(--text-light);
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .size-btn:hover,
    .size-btn.active {
        background: var(--accent-blue);
        border-color: var(--accent-blue);
        color: white;
    }
    
    .merchandise-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-whatsapp {
        flex: 1;
        padding: 12px 16px;
        background: #25D366;
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-whatsapp:hover {
        background: #128C7E;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37, 211, 102, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-secondary {
        padding: 12px 16px;
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
    
    .whatsapp-info {
        background: rgba(37, 211, 102, 0.1);
        border: 1px solid rgba(37, 211, 102, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .whatsapp-info h3 {
        color: #25D366;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .whatsapp-info p {
        color: #94A3B8 !important;
        margin-bottom: 0;
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 2.5rem;
        }
        
        .merchandise-grid {
            grid-template-columns: 1fr;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .merchandise-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="merchandise-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Epic Merchandise</h1>
            <p class="page-subtitle">Get exclusive Epic Events merchandise and show your epic style</p>
        </div>
        
        <!-- WhatsApp Info -->
        <div class="whatsapp-info">
            <h3>
                <i class="fab fa-whatsapp"></i>
                Order via WhatsApp
            </h3>
            <p>Click "Order Now" on any item to send your order directly via WhatsApp. We'll help you complete your purchase!</p>
        </div>
        
        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('merchandise') }}">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Category</label>
                        <select name="category" class="filter-control">
                            <option value="">All Categories</option>
                            <option value="Clothing" {{ request('category') == 'Clothing' ? 'selected' : '' }}>Clothing</option>
                            <option value="Accessories" {{ request('category') == 'Accessories' ? 'selected' : '' }}>Accessories</option>
                            <option value="Stationery" {{ request('category') == 'Stationery' ? 'selected' : '' }}>Stationery</option>
                            <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Price Range</label>
                        <select name="price" class="filter-control">
                            <option value="">Any Price</option>
                            <option value="0-50000" {{ request('price') == '0-50000' ? 'selected' : '' }}>Under Rp 50K</option>
                            <option value="50000-150000" {{ request('price') == '50000-150000' ? 'selected' : '' }}>Rp 50K - 150K</option>
                            <option value="150000-300000" {{ request('price') == '150000-300000' ? 'selected' : '' }}>Rp 150K - 300K</option>
                            <option value="300000+" {{ request('price') == '300000+' ? 'selected' : '' }}>Above Rp 300K</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Availability</label>
                        <select name="stock" class="filter-control">
                            <option value="">All Items</option>
                            <option value="available" {{ request('stock') == 'available' ? 'selected' : '' }}>In Stock</option>
                            <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Low Stock</option>
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
        
        <!-- Merchandise Grid -->
        <div class="merchandise-grid">
            @if(isset($merchandise) && $merchandise->count() > 0)
                @foreach($merchandise as $item)
                <div class="merchandise-card">
                    <div class="merchandise-image">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="merchandise-img">
                        @else
                            <div class="placeholder">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                        @endif
                        <div class="stock-badge {{ $item->stock > 10 ? 'stock-available' : ($item->stock > 0 ? 'stock-low' : 'stock-out') }}">
                            @if($item->stock > 10)
                                In Stock
                            @elseif($item->stock > 0)
                                Low Stock
                            @else
                                Out of Stock
                            @endif
                        </div>
                    </div>
                    <div class="merchandise-content">
                        <div class="merchandise-category">{{ $item->category ?? 'General' }}</div>
                        <h3 class="merchandise-title">{{ $item->name }}</h3>
                        <p class="merchandise-description">
                            {{ Str::limit($item->description, 100) }}
                        </p>
                        <div class="merchandise-meta">
                            <div class="merchandise-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            <div class="merchandise-stock">{{ $item->stock }} left</div>
                        </div>
                        <div class="merchandise-actions">
                            @if($item->stock > 0)
                                <a href="#" class="btn-whatsapp" onclick="orderViaWhatsApp({{ json_encode($item->name) }}, {{ json_encode('Rp ' . number_format($item->price, 0, ',', '.')) }}, 'Standard')">
                                    <i class="fab fa-whatsapp"></i>Order Now
                                </a>
                            @else
                                <button class="btn-whatsapp disabled" disabled>
                                    <i class="fas fa-times"></i>Out of Stock
                                </button>
                            @endif
                         
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-shopping-bag" style="font-size: 4rem; color: rgba(255, 255, 255, 0.3);"></i>
                    </div>
                    <h5 style="color: #F8FAFC !important;" class="mb-3">No merchandise available</h5>
                    <p style="color: #94A3B8 !important;">Check back later for new items!</p>
                </div>
            @endif
         </div>
         
         <!-- Pagination -->
         @if(isset($merchandise) && method_exists($merchandise, 'links'))
             <div class="d-flex justify-content-center mt-5">
                 <nav aria-label="Merchandise pagination">
                     <div class="pagination-wrapper" style="background: rgba(255, 255, 255, 0.1); border-radius: 10px; padding: 15px;">
                         {{ $merchandise->appends(request()->query())->links('pagination::bootstrap-4') }}
                     </div>
                 </nav>
             </div>
         @endif
    </div>
</div>

<!-- Additional CSS for merchandise image -->
@push('styles')
<style>
    .merchandise-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px 10px 0 0;
    }
    
    .stock-out {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
    }
    
    .btn-whatsapp.disabled {
        background: #6c757d;
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .btn-whatsapp.disabled:hover {
        transform: none;
        box-shadow: none;
    }
</style>
@endpush

<script>
function orderViaWhatsApp(itemName, price, size) {
    console.log('orderViaWhatsApp called with:', itemName, price, size);
    
    const message = `Hi! I would like to order:\n\n` +
                   `Item: ${itemName}\n` +
                   `Price: ${price}\n` +
                   `Size/Variant: ${size}\n\n` +
                   `Please let me know the next steps for payment and delivery. Thank you!`;
    
    const whatsappNumber = '62895363334689'; // Replace with actual WhatsApp number
    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
    
    console.log('Opening WhatsApp URL:', whatsappUrl);
    
    // Try to open WhatsApp
    const opened = window.open(whatsappUrl, '_blank');
    
    if (!opened) {
        alert('Pop-up blocked! Please allow pop-ups for this site or copy this link: ' + whatsappUrl);
    }
}

// Test function availability on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Merchandise page loaded, orderViaWhatsApp function available:', typeof orderViaWhatsApp === 'function');
});
</script>

@endsection