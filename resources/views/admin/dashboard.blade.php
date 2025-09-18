@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .admin-dashboard {
        padding: 2rem 0;
        min-height: calc(100vh - 80px);
    }
    
    .dashboard-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .dashboard-title {
        font-family: 'Orbitron', monospace;
        font-size: 2.5rem;
        font-weight: 700;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }
    
    .dashboard-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 10px 20px;
        background: var(--gradient-accent);
        border: none;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        color: white;
        text-decoration: none;
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
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .stat-title {
        color: var(--text-gray);
        font-size: 0.9rem;
        font-weight: 500;
        margin: 0;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
    }
    
    .stat-icon.events {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-icon.merchandise {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stat-icon.registrations {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stat-icon.revenue {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .stat-icon.collaborations {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-light);
        margin: 0;
        font-family: 'Orbitron', monospace;
    }
    
    .stat-change {
        font-size: 0.8rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .stat-change.positive {
        color: #10B981;
    }
    
    .stat-change.negative {
        color: #EF4444;
    }
    
    .dashboard-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .main-content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .sidebar-content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .dashboard-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .card-title {
        font-family: 'Orbitron', monospace;
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--text-light);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-title i {
        color: var(--accent-blue);
    }
    
    .btn-view-all {
        color: var(--accent-blue);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-view-all:hover {
        color: var(--text-light);
        text-decoration: none;
    }
    
    .recent-events {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .event-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .event-item:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }
    
    .event-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        background: var(--gradient-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .event-info {
        flex: 1;
    }
    
    .event-name {
        color: var(--text-light);
        font-weight: 600;
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
    }
    
    .event-date {
        color: var(--text-gray);
        font-size: 0.85rem;
        margin: 0;
    }
    
    .event-status {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .status-active {
        background: rgba(16, 185, 129, 0.2);
        color: #10B981;
    }
    
    .status-upcoming {
        background: rgba(59, 130, 246, 0.2);
        color: #3B82F6;
    }
    
    .status-completed {
        background: rgba(107, 114, 128, 0.2);
        color: #6B7280;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .quick-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 1.5rem 1rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        text-decoration: none;
        color: var(--text-gray);
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    
    .quick-action:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-light);
        text-decoration: none;
        border-color: var(--accent-blue);
        transform: translateY(-2px);
    }
    
    .quick-action i {
        font-size: 2rem;
        color: var(--accent-blue);
    }
    
    .quick-action span {
        font-weight: 500;
        font-size: 0.9rem;
        text-align: center;
    }
    
    .activity-feed {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        color: white;
        flex-shrink: 0;
    }
    
    .activity-icon.registration {
        background: #3B82F6;
    }
    
    .activity-icon.event {
        background: #8B5CF6;
    }
    
    .activity-icon.merchandise {
        background: #EF4444;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-text {
        color: var(--text-light);
        font-size: 0.9rem;
        margin: 0 0 0.25rem 0;
    }
    
    .activity-time {
        color: var(--text-gray);
        font-size: 0.8rem;
        margin: 0;
    }
    
    .notifications {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .notification-item {
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        border-left: 3px solid var(--accent-blue);
    }
    
    .notification-item.warning {
        border-left-color: #F59E0B;
    }
    
    .notification-item.error {
        border-left-color: #EF4444;
    }
    
    .notification-title {
        color: var(--text-light);
        font-weight: 600;
        font-size: 0.9rem;
        margin: 0 0 0.25rem 0;
    }
    
    .notification-text {
        color: var(--text-gray);
        font-size: 0.8rem;
        margin: 0;
    }
    
    @media (max-width: 1024px) {
        .dashboard-content {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }
    
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 2rem;
        }
        
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
        
        .dashboard-card {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="admin-dashboard">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">Admin Dashboard</h1>
            <div class="dashboard-actions">
                <a href="{{ route('admin.events.create') }}" class="btn-action">
                    <i class="fas fa-plus"></i>
                    New Event
                </a>
                <a href="{{ route('admin.merchandise.create') }}" class="btn-action">
                    <i class="fas fa-plus"></i>
                    Add Merchandise
                </a>
            </div>
        </div>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Total Events</h3>
                    <div class="stat-icon events">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <p class="stat-value">{{ $totalEvents ?? 12 }}</p>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    +2 this month
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Active Registrations</h3>
                    <div class="stat-icon registrations">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <p class="stat-value">{{ $totalRegistrations ?? 248 }}</p>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    +15 today
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Merchandise Items</h3>
                    <div class="stat-icon merchandise">
                        <i class="fas fa-tshirt"></i>
                    </div>
                </div>
                <p class="stat-value">{{ $totalMerchandise ?? 36 }}</p>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    +4 this week
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Collaborations</h3>
                    <div class="stat-icon collaborations">
                        <i class="fas fa-handshake"></i>
                    </div>
                </div>
                <p class="stat-value">{{ $totalCollaborations ?? 0 }}</p>
                <div class="stat-change {{ $pendingCollaborations > 0 ? 'positive' : '' }}">
                    <i class="fas fa-clock"></i>
                    {{ $pendingCollaborations ?? 0 }} pending
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Monthly Revenue</h3>
                    <div class="stat-icon revenue">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <p class="stat-value">{{ $monthlyRevenue ?? '85M' }}</p>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    +12% vs last month
                </div>
            </div>
        </div>
        
        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Main Content -->
            <div class="main-content">
                <!-- Recent Events -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-calendar-alt"></i>
                            Recent Events
                        </h2>
                        <a href="{{ route('admin.events.index') }}" class="btn-view-all">View All</a>
                    </div>
                    
                    <div class="recent-events">
                        <div class="event-item">
                            <div class="event-image">
                                <i class="fas fa-music"></i>
                            </div>
                            <div class="event-info">
                                <h4 class="event-name">Summer Music Festival 2024</h4>
                                <p class="event-date">March 15, 2024 • 7:00 PM</p>
                            </div>
                            <span class="event-status status-active">Active</span>
                        </div>
                        
                        <div class="event-item">
                            <div class="event-image">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="event-info">
                                <h4 class="event-name">Tech Conference 2024</h4>
                                <p class="event-date">March 20, 2024 • 9:00 AM</p>
                            </div>
                            <span class="event-status status-upcoming">Upcoming</span>
                        </div>
                        
                        <div class="event-item">
                            <div class="event-image">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="event-info">
                                <h4 class="event-name">Charity Gala Night</h4>
                                <p class="event-date">March 10, 2024 • 6:00 PM</p>
                            </div>
                            <span class="event-status status-completed">Completed</span>
                        </div>
                        
                        <div class="event-item">
                            <div class="event-image">
                                <i class="fas fa-gamepad"></i>
                            </div>
                            <div class="event-info">
                                <h4 class="event-name">Gaming Tournament</h4>
                                <p class="event-date">March 25, 2024 • 2:00 PM</p>
                            </div>
                            <span class="event-status status-upcoming">Upcoming</span>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-activity"></i>
                            Recent Activity
                        </h2>
                    </div>
                    
                    <div class="activity-feed">
                        <div class="activity-item">
                            <div class="activity-icon registration">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text">New registration for Summer Music Festival 2024</p>
                                <p class="activity-time">2 minutes ago</p>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon event">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text">Gaming Tournament event created</p>
                                <p class="activity-time">1 hour ago</p>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon merchandise">
                                <i class="fas fa-tshirt"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text">New merchandise item added: Epic Events Hoodie</p>
                                <p class="activity-time">3 hours ago</p>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon registration">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text">5 new registrations for Tech Conference 2024</p>
                                <p class="activity-time">5 hours ago</p>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon event">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text">Charity Gala Night event updated</p>
                                <p class="activity-time">1 day ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Content -->
            <div class="sidebar-content">
                <!-- Quick Actions -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-bolt"></i>
                            Quick Actions
                        </h2>
                    </div>
                    
                    <div class="quick-actions">
                        <a href="{{ route('admin.events.create') }}" class="quick-action">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Create Event</span>
                        </a>
                        
                        <a href="{{ route('admin.merchandise.create') }}" class="quick-action">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add Merchandise</span>
                        </a>
                        
                        <a href="{{ route('admin.registrations.index') }}" class="quick-action">
                            <i class="fas fa-list-alt"></i>
                            <span>View Registrations</span>
                        </a>
                        
                        <a href="{{ route('admin.analytics') }}" class="quick-action">
                            <i class="fas fa-chart-bar"></i>
                            <span>Analytics</span>
                        </a>
                    </div>
                </div>
                
                <!-- Notifications -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-bell"></i>
                            Notifications
                        </h2>
                    </div>
                    
                    <div class="notifications">
                        <div class="notification-item">
                            <h4 class="notification-title">Event Reminder</h4>
                            <p class="notification-text">Summer Music Festival starts in 3 days. 156 registrations so far.</p>
                        </div>
                        
                        <div class="notification-item warning">
                            <h4 class="notification-title">Low Stock Alert</h4>
                            <p class="notification-text">Epic Events T-Shirt (Size M) is running low. Only 5 items left.</p>
                        </div>
                        
                        <div class="notification-item">
                            <h4 class="notification-title">New Collaboration</h4>
                            <p class="notification-text">You have 3 new collaboration requests to review.</p>
                        </div>
                        
                        <div class="notification-item error">
                            <h4 class="notification-title">Payment Issue</h4>
                            <p class="notification-text">2 registrations have pending payment verification.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection