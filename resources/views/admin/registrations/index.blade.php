@extends('layouts.admin')

@section('title', 'Registrations Management')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@push('styles')
<style>
    .registration-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    
    .registration-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 123, 255, 0.3);
    }
    
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #ffc107, #ff8f00);
        color: #000;
    }
    
    .status-confirmed {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
    }
    
    .status-cancelled {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
    }
    
    .table-futuristic {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        overflow: hidden;
    }
    
    .table-futuristic th {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        border: none;
        padding: 15px;
        font-family: 'Orbitron', monospace;
    }
    
    .table-futuristic td {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 12px 15px;
        color: #e0e6ed;
    }
    
    .table-futuristic tbody tr:hover {
        background: rgba(0, 123, 255, 0.1);
    }
    
    .btn-action {
        padding: 8px 15px;
        border-radius: 8px;
        border: none;
        font-size: 0.85rem;
        margin: 0 2px;
        transition: all 0.3s ease;
    }
    
    .btn-view {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: #fff;
    }
    
    .btn-email {
        background: linear-gradient(135deg, #007bff, #0056b3) !important;
        color: #fff !important;
        border: none !important;
    }
    
    .btn-email:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        background: linear-gradient(135deg, #0056b3, #007bff) !important;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #000;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-white mb-1" style="font-family: 'Orbitron', monospace;">Event Registrations</h2>
            <p class="text-white mb-0">Manage event registrations and participants</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.registrations.export.excel') }}" class="btn btn-primary">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </a>
           
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="registration-card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h5 class="text-white mb-0">{{ $stats['total'] }}</h5>
                        <small class="text-muted">Total Registrations</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="registration-card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div>
                        <h5 class="text-white mb-0">{{ $stats['confirmed'] }}</h5>
                        <small class="text-muted">Confirmed</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="registration-card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h5 class="text-white mb-0">{{ $stats['pending'] }}</h5>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="registration-card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                    <div>
                        <h5 class="text-white mb-0">{{ $stats['cancelled'] }}</h5>
                        <small class="text-muted">Cancelled</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.registrations.index') }}" id="filterForm">
        <div class="registration-card p-3 mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label text-white">Event</label>
                    <select class="form-select" name="event_id" id="eventFilter">
                        <option value="">All Events</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-white">Status</label>
                    <select class="form-select" name="status" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-white">From Date</label>
                    <input type="date" class="form-control" name="date_from" id="dateFrom" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-white">To Date</label>
                    <input type="date" class="form-control" name="date_to" id="dateTo" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-white">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Search by name or email...">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="form-label text-white">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger d-block" id="clearFilters" title="Clear Filters">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Registrations Table -->
    <div class="table-futuristic">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Participant</th>
                    <th>Event</th>
                    <th>Registration Date</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $index => $registration)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div>
                            <strong class="text-white">{{ $registration->user->name }}</strong><br>
                            <small class="text-muted">{{ $registration->user->email }}</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong class="text-white">{{ $registration->event->title }}</strong><br>
                            <small class="text-muted">
                                @if($registration->event->event_date)
                                    {{ $registration->event->event_date->format('M d, Y') }}
                                @else
                                    Date not set
                                @endif
                            </small>
                        </div>
                    </td>
                    <td>
                        @if($registration->registered_at)
                            <span class="text-white">{{ $registration->registered_at->format('M d, Y') }}</span><br>
                            <small class="text-muted">{{ $registration->registered_at->format('g:i A') }}</small>
                        @else
                            <span class="text-muted">Not set</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-{{ $registration->status == 'confirmed' ? 'confirmed' : ($registration->status == 'cancelled' ? 'cancelled' : 'pending') }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </td>
                    <td>
                        @if($registration->event->price > 0)
                            @if($registration->payment)
                                @if($registration->payment->payment_proof)
                                    <a href="{{ Storage::disk('public_uploads')->url($registration->payment->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat Bukti Transfer">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @else
                                    <span class="text-white">-</span>
                                @endif
                            @else
                                <span class="text-white">-</span>
                            @endif
                        @else
                            <span class="text-success">Free</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-action btn-view" title="View Details" onclick="viewRegistration({{ $registration->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if($registration->user->email)
                                        <button class="btn btn-action btn-email" title="Send Email Notification" onclick="sendEmailNotification({{ json_encode($registration->user->email) }}, {{ json_encode($registration->user->name) }}, {{ json_encode($registration->event->title) }}, {{ json_encode($registration->status) }}, {{ $registration->id }})" style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; border: none; margin: 0 2px;">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                        @endif
                        <button class="btn btn-action btn-delete" title="Delete" onclick="deleteRegistration({{ $registration->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-white py-4">
                        <i class="fas fa-inbox fa-3x mb-3 text-muted"></i><br>
                        No registrations found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($registrations->hasPages())
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Registrations pagination">
            <div class="pagination-wrapper">
                {{ $registrations->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </nav>
    </div>
    @endif
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: linear-gradient(135deg, #1e3c72, #2a5298); border: 1px solid rgba(255, 255, 255, 0.2);">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title text-white">Export Registration Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label text-white">Export Format</label>
                    <select class="form-select">
                        <option>Excel (.xlsx)</option>
                        <option>CSV (.csv)</option>
                        <option>PDF Report</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white">Date Range</label>
                    <div class="row">
                        <div class="col-6">
                            <input type="date" class="form-control" placeholder="From">
                        </div>
                        <div class="col-6">
                            <input type="date" class="form-control" placeholder="To">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white">Include Fields</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label text-white">Participant Details</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label text-white">Event Information</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label text-white">Payment Status</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Export Data</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>// View registration details
    function viewRegistration(id) {
        // Redirect to detail page
        window.location.href = `{{ route('admin.registrations.index') }}/${id}`;
    }

    function updateStatus(id, status) {
        if (confirm(`Apakah Anda yakin ingin mengubah status menjadi ${status}?`)) {
            fetch(`/admin/registrations/${id}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    throw new Error('Response is not JSON');
                }
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal mengupdate status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }
    }

    function deleteRegistration(id) {
        if (confirm('Apakah Anda yakin ingin menghapus registrasi ini?')) {
            fetch(`/admin/registrations/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    throw new Error('Response is not JSON');
                }
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus registrasi');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }
    }

    // Email notification function
    function sendEmailNotification(email, userName, eventName, status, registrationId) {
        if (!email) {
            showAlert('error', 'Email tidak tersedia!');
            return;
        }
        
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
        button.disabled = true;
        
        // Send AJAX request to send email notification
        fetch('{{ route("admin.email.send-notification") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                registration_id: registrationId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                throw new Error('Response is not JSON');
            }
        })
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat mengirim email');
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
    
    function showAlert(type, message) {
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        alertDiv.style.minWidth = '300px';
        
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Add to page
        document.body.appendChild(alertDiv);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
            }
        }, 5000);
    }

    // Filter and search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[placeholder="Search by name or email..."]');
        const eventFilter = document.querySelectorAll('select')[0];
        const statusFilter = document.querySelectorAll('select')[1];
        const dateInput = document.querySelector('input[type="date"]');
        
        // Create form for filters
        const filterForm = document.createElement('form');
        filterForm.method = 'GET';
        filterForm.action = window.location.pathname;
        
        // Auto-submit form when filters change
        if (eventFilter) {
            eventFilter.addEventListener('change', function() {
                updateFilters();
            });
        }
        
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                updateFilters();
            });
        }
        
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                updateFilters();
            });
        }
        
        // Search with debounce
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    updateFilters();
                }, 500);
            });
        }
        
        function updateFilters() {
            const params = new URLSearchParams();
            
            if (eventFilter && eventFilter.value && eventFilter.value !== 'All Events') {
                params.append('event', eventFilter.value);
            }
            
            if (statusFilter && statusFilter.value && statusFilter.value !== 'All Status') {
                params.append('status', statusFilter.value.toLowerCase());
            }
            
            if (dateInput && dateInput.value) {
                params.append('date', dateInput.value);
            }
            
            if (searchInput && searchInput.value) {
                params.append('search', searchInput.value);
            }
            
            const queryString = params.toString();
            const newUrl = queryString ? `${window.location.pathname}?${queryString}` : window.location.pathname;
            window.location.href = newUrl;
        }
    });
</script>
@endpush

@endsection