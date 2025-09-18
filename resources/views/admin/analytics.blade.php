@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div class="analytics-container">
    <div class="page-header">
        <h1>Analytics Dashboard</h1>
        <p>Comprehensive analytics and insights</p>
    </div>

    <!-- Summary Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalEvents }}</h3>
                <p>Total Events</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalUsers }}</h3>
                <p>Total Users</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-list-alt"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalRegistrations }}</h3>
                <p>Total Registrations</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalMerchandise }}</h3>
                <p>Merchandise Items</p>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="analytics-grid">
        <!-- Monthly Registrations Chart -->
        <div class="analytics-card">
            <div class="card-header">
                <h3>Monthly Registrations ({{ date('Y') }})</h3>
            </div>
            <div class="chart-container">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Popular Events -->
        <div class="analytics-card">
            <div class="card-header">
                <h3>Most Popular Events</h3>
            </div>
            <div class="popular-events">
                @forelse($popularEvents as $event)
                    <div class="event-item">
                        <div class="event-info">
                            <h4>{{ $event->title }}</h4>
                            <p>{{ $event->registrations_count }} registrations</p>
                        </div>
                        <div class="event-progress">
                            @php
                                $maxRegistrations = max($popularEvents->pluck('registrations_count')->toArray());
                                $progressWidth = $maxRegistrations > 0 ? ($event->registrations_count / $maxRegistrations) * 100 : 0;
                            @endphp
                            <div class="progress-bar" style="width: {{ $progressWidth }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="no-data">No events found</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="analytics-card full-width">
        <div class="card-header">
            <h3>Recent Activity</h3>
        </div>
        <div class="activity-list">
            @forelse($recentActivity as $activity)
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <p><strong>{{ $activity->user->name }}</strong> registered for <strong>{{ $activity->event->title }}</strong></p>
                        <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="activity-status status-{{ $activity->status }}">
                        {{ ucfirst($activity->status) }}
                    </div>
                </div>
            @empty
                <p class="no-data">No recent activity</p>
            @endforelse
        </div>
    </div>
</div>

<style>
.analytics-container {
    padding: 20px;
}

.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    color: #1a365d;
    margin-bottom: 5px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
}

.stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stat-content h3 {
    font-size: 32px;
    font-weight: bold;
    color: #1a365d;
    margin: 0;
}

.stat-content p {
    color: #718096;
    margin: 5px 0 0 0;
}

.analytics-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

.analytics-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.analytics-card.full-width {
    grid-column: 1 / -1;
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    background: #f7fafc;
}

.card-header h3 {
    margin: 0;
    color: #1a365d;
}

.chart-container {
    padding: 20px;
    height: 300px;
}

.popular-events {
    padding: 20px;
}

.event-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #e2e8f0;
}

.event-item:last-child {
    border-bottom: none;
}

.event-info h4 {
    margin: 0 0 5px 0;
    color: #1a365d;
}

.event-info p {
    margin: 0;
    color: #718096;
    font-size: 14px;
}

.event-progress {
    width: 100px;
    height: 8px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: width 0.3s ease;
}

.activity-list {
    padding: 20px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #e2e8f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: #e2e8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #718096;
}

.activity-content {
    flex: 1;
}

.activity-content p {
    margin: 0 0 5px 0;
    color: #1a365d;
}

.activity-time {
    font-size: 12px;
    color: #718096;
}

.activity-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-confirmed {
    background: #c6f6d5;
    color: #22543d;
}

.status-cancelled {
    background: #fed7d7;
    color: #c53030;
}

.no-data {
    text-align: center;
    color: #718096;
    padding: 40px;
    font-style: italic;
}

@media (max-width: 768px) {
    .analytics-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Registrations Chart
const monthlyData = @json($monthlyRegistrations);
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const chartData = new Array(12).fill(0);

monthlyData.forEach(item => {
    chartData[item.month - 1] = item.count;
});

const ctx = document.getElementById('monthlyChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Registrations',
            data: chartData,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#e2e8f0'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
@endsection