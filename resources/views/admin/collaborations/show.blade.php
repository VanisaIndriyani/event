@extends('layouts.admin')

@section('title', 'Detail Kolaborasi')

@push('styles')
<style>
.collaboration-detail {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.detail-header {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
}

.detail-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: linear-gradient(45deg, #fff, #e0e7ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.detail-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.detail-card-header {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    padding: 1.5rem;
    border-bottom: none;
}

.detail-card-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.25rem;
}

.detail-card-body {
    padding: 2rem;
    color: #374151;
}

.detail-card-body h5,
.detail-card-body h6 {
    color: #1f2937;
}

.detail-card-body p,
.detail-card-body div {
    color: #374151;
}

.info-table {
    margin: 0;
}

.info-table td {
    padding: 0.75rem 0;
    border: none;
    vertical-align: top;
}

.info-table td:first-child {
    font-weight: 600;
    color: #4f46e5;
    width: 30%;
}

.info-table td:last-child {
    color: #374151;
    font-weight: 500;
}

.service-badge {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 500;
    margin: 0.25rem;
    display: inline-block;
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.status-approved {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.status-rejected {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-back {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 15px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    transform: translateY(-2px);
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-action {
    padding: 0.75rem 2rem;
    border-radius: 15px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-approve {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-reject {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.description-text {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 15px;
    border-left: 4px solid #4f46e5;
    font-style: italic;
    line-height: 1.6;
    color: #1f2937;
    font-weight: 500;
}

.form-select,
.form-control {
    color: #374151;
    background-color: #ffffff;
    border: 1px solid #d1d5db;
}

.form-select:focus,
.form-control:focus {
    color: #1f2937;
    background-color: #ffffff;
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
}

.form-label {
    color: #374151;
    font-weight: 600;
}

@media (max-width: 768px) {
    .detail-header {
        padding: 1.5rem;
    }
    
    .detail-header h1 {
        font-size: 2rem;
    }
    
    .detail-card-body {
        padding: 1.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endpush

@section('content')
<div class="collaboration-detail">
    <div class="container-fluid">
        <!-- Header -->
        <div class="detail-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Collaboration Details</h1>
                    <p class="mb-0 opacity-75">ID: #{{ $collaboration->id }}</p>
                </div>
                <a href="{{ route('admin.collaborations.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to List
                </a>
            </div>
        </div>
                
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #059669;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Contact Information -->
            <div class="col-md-6">
                <div class="detail-card">
                    <div class="detail-card-header">
                        <h5><i class="fas fa-user me-2"></i>Contact Information</h5>
                    </div>
                    <div class="detail-card-body">
                        <table class="info-table table">
                            <tr>
                                <td>Name:</td>
                                <td>{{ $collaboration->name }}</td>
                            </tr>
                            <tr>
                                <td>Organization:</td>
                                <td>{{ $collaboration->organization ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>
                                    <a href="mailto:{{ $collaboration->email }}" style="color: #4f46e5; text-decoration: none;">
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ $collaboration->email }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Phone:</td>
                                <td>
                                                    <a href="tel:{{ $collaboration->phone }}" style="color: #4f46e5; text-decoration: none;">
                                        <i class="fas fa-phone me-1"></i>
                                        {{ $collaboration->phone }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Submission Date:</td>
                                <td>{{ $collaboration->created_at->format('d F Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Collaboration Details -->
            <div class="col-md-6">
                <div class="detail-card">
                    <div class="detail-card-header">
                        <h5><i class="fas fa-handshake me-2"></i>Collaboration Details</h5>
                    </div>
                    <div class="detail-card-body">
                        <table class="info-table table">
                            <tr>
                                <td>Collaboration Type:</td>
                                <td>
                                    @switch($collaboration->collaboration_type)
                                        @case('corporate')
                                                        <span class="badge bg-primary">Corporate Events</span>
                                                        @break
                                                    @case('brand')
                                                        <span class="badge bg-info">Brand Partnership</span>
                                                        @break
                                                    @case('community')
                                                        <span class="badge bg-success">Community Events</span>
                                                        @break
                                                    @case('custom')
                                                        <span class="badge bg-warning">Custom Experience</span>
                                                        @break
                                                    @case('sponsorship')
                                                        <span class="badge bg-secondary">Sponsorship</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-dark">{{ ucfirst($collaboration->collaboration_type) }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Budget:</strong></td>
                                            <td>
                                                @if($collaboration->budget)
                                                    @switch($collaboration->budget)
                                                        @case('under-10m')
                                                            Under Rp 10 Million
                                                            @break
                                                        @case('10m-25m')
                                                            Rp 10 - 25 Million
                                                            @break
                                                        @case('25m-50m')
                                                            Rp 25 - 50 Million
                                                            @break
                                                        @case('50m-100m')
                                                            Rp 50 - 100 Million
                                                            @break
                                                        @case('over-100m')
                                                            Over Rp 100 Million
                                                            @break
                                                        @default
                                                            {{ $collaboration->budget }}
                                                    @endswitch
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Timeline:</strong></td>
                                            <td>
                                                @if($collaboration->timeline)
                                                    @switch($collaboration->timeline)
                                                        @case('asap')
                                                            ASAP (Within 1 month)
                                                            @break
                                                        @case('1-3months')
                                                            1-3 months
                                                            @break
                                                        @case('3-6months')
                                                            3-6 months
                                                            @break
                                                        @case('6months+')
                                                            6+ months
                                                            @break
                                                        @case('flexible')
                                                            Flexible
                                                            @break
                                                        @default
                                                            {{ $collaboration->timeline }}
                                                    @endswitch
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                            <tr>
                                <td>Status:</td>
                                <td>
                                    @php
                                        $statusClass = match($collaboration->status) {
                                            'pending' => 'status-pending',
                                            'approved' => 'status-approved',
                                            'rejected' => 'status-rejected',
                                            default => 'status-pending'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        <i class="fas fa-circle me-1"></i>
                                        {{ ucfirst($collaboration->status) }}
                                    </span>
                                </td>
                            </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

        <!-- Required Services -->
        @if($collaboration->services && !empty($collaboration->services))
        <div class="detail-card">
            <div class="detail-card-header">
                <h5><i class="fas fa-cogs me-2"></i>Required Services</h5>
            </div>
            <div class="detail-card-body">
                <div class="row">
                    @php
                        $services = is_string($collaboration->services) ? json_decode($collaboration->services, true) : $collaboration->services;
                        $services = $services ?? [];
                    @endphp
                    @foreach($services as $service)
                        <div class="col-md-4 mb-3">
                            <span class="service-badge">
                                <i class="fas fa-check me-2"></i>
                                @switch($service)
                                    @case('planning')
                                        Event Planning
                                        @break
                                    @case('marketing')
                                        Marketing & Promotion
                                        @break
                                    @case('venue')
                                        Venue Management
                                        @break
                                    @case('catering')
                                        Catering Services
                                        @break
                                    @case('entertainment')
                                        Entertainment
                                        @break
                                    @case('photography')
                                        Photography/Videography
                                        @break
                                    @default
                                        {{ ucfirst($service) }}
                                @endswitch
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Project Description -->
        @if($collaboration->event_description)
        <div class="detail-card">
            <div class="detail-card-header">
                <h5><i class="fas fa-file-alt me-2"></i>Project Description</h5>
            </div>
            <div class="detail-card-body">
                <div class="description-text">
                    {{ $collaboration->event_description }}
                </div>
            </div>
        </div>
        @endif

        <!-- Additional Information -->
        @if($collaboration->additional_info)
        <div class="detail-card">
            <div class="detail-card-header">
                <h5><i class="fas fa-info-circle me-2"></i>Additional Information</h5>
            </div>
            <div class="detail-card-body">
                <div class="description-text">
                    {{ $collaboration->additional_info }}
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5><i class="fas fa-tasks me-2"></i>Actions</h5>
            </div>
            <div class="detail-card-body">
                <form method="POST" action="{{ route('admin.collaborations.update-status', $collaboration->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-bold">Update Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending" {{ $collaboration->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewing" {{ $collaboration->status == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                <option value="approved" {{ $collaboration->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $collaboration->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="completed" {{ $collaboration->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-action btn-approve w-100">
                                <i class="fas fa-save me-2"></i>
                                Update Status
                            </button>
                        </div>
                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection