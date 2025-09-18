@extends('layouts.app')

@section('title', 'Dashboard - Epic Events')

@push('styles')
<style>
    .dashboard-container {
        padding: 2rem 0;
        min-height: calc(100vh - 80px);
    }
    
    .welcome-section {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .welcome-title {
        font-family: 'Orbitron', monospace;
        font-size: 2.5rem;
        font-weight: 700;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }
    
    .welcome-subtitle {
        color: var(--text-gray);
        font-size: 1.1rem;
        margin-bottom: 0;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    
    .stat-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-accent);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }
    
    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-light);
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: var(--text-gray);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .section-title {
        font-family: 'Orbitron', monospace;
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: var(--accent-blue);
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    
    .action-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }
    
    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        text-decoration: none;
        color: inherit;
    }
    
    .action-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .action-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 0.5rem;
    }
    
    .action-description {
        color: var(--text-gray);
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    .recent-activity {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 2rem;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--gradient-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-title {
        color: var(--text-light);
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    
    .activity-time {
        color: var(--text-gray);
        font-size: 0.8rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-gray);
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .welcome-title {
            font-size: 2rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
        
        .dashboard-container {
            padding: 1rem 0;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1 class="welcome-title">Welcome Back, {{ Auth::user()->name }}!</h1>
            <p class="welcome-subtitle">Ready for your next epic adventure?</p>
        </div>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-number">{{ $registeredEvents ?? 0 }}</div>
                <div class="stat-label">Events Registered</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-number">{{ $merchandiseOrders ?? 0 }}</div>
                <div class="stat-label">Merchandise Orders</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-number">{{ $collaborations ?? 0 }}</div>
                <div class="stat-label">Collaborations</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-number">{{ $totalPoints ?? 0 }}</div>
                <div class="stat-label">Epic Points</div>
            </div>
        </div>
        
      
        
        <!-- Recent Activity -->
        <h2 class="section-title">
            <i class="fas fa-history"></i>
            Recent Activity
        </h2>
        
        <div class="recent-activity">
            @if(isset($recentActivities) && count($recentActivities) > 0)
                @foreach($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="{{ $activity['icon'] }}"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">{{ $activity['title'] }}</div>
                            <div class="activity-time">{{ $activity['time'] }}</div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-rocket"></i>
                    <h3>No Recent Activity</h3>
                    <p>Start exploring events and merchandise to see your activity here!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection