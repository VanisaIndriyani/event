@extends('layouts.admin')

@section('title', 'Registration Details')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@push('styles')
<style>
    .registration-detail-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(30, 58, 138, 0.3);
    }
    
    .detail-section {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 10px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }
    
    .detail-label {
        color: #1e40af;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .detail-value {
        color: #1f2937;
        font-size: 1.1rem;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
    }
    
    .status-confirmed { background: #1e40af; color: white; }
    .status-pending { background: #f59e0b; color: white; }
    .status-cancelled { background: #ef4444; color: white; }
    
    .payment-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
    }
    
    .payment-verified { background: #1e40af; color: white; }
    .payment-pending { background: #f59e0b; color: white; }
    .payment-rejected { background: #ef4444; color: white; }
    .payment-none { background: #6b7280; color: white; }
    
    .method-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        background: #1e40af;
        color: white;
        display: inline-flex;
        align-items: center;
        text-transform: capitalize;
    }
    
    .action-btn {
        background: #1e40af;
        border: none;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(30, 64, 175, 0.3);
        color: white;
        background: #1d4ed8;
    }
    
    .btn-back {
        background: #6b7280;
    }
    
    .btn-update {
        background: #1e40af;
    }
    
    .btn-delete {
        background: #ef4444;
    }
    
    /* New Styles for Improved Layout */
    .icon-wrapper {
        width: 50px;
        height: 50px;
        background: #1e40af;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
    }
    
    .info-card {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(30, 64, 175, 0.2);
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .info-card:hover {
        background: rgba(255, 255, 255, 1);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(30, 64, 175, 0.15);
    }
    
    .info-header {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }
    
    .info-header i {
        font-size: 1.1rem;
        margin-right: 8px;
        color: #1e40af;
    }
    
    .info-label {
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        font-size: 1.1rem;
        color: #1f2937;
        font-weight: 600;
        line-height: 1.4;
    }
    
    .notes-section {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(30, 64, 175, 0.3);
        border-radius: 12px;
        padding: 20px;
    }
    
    .notes-header {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }
    
    .notes-label {
        font-size: 0.9rem;
        color: #1e40af;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .notes-content {
        color: #1f2937;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    
    .avatar-wrapper {
        width: 45px;
        height: 45px;
        background: #1e40af;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: white;
    }
    
    .participant-info {
        space-y: 16px;
    }
    
    .participant-item {
        display: flex;
        align-items: center;
        padding: 16px;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(30, 64, 175, 0.2);
        border-radius: 10px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }
    
    .participant-item:hover {
        background: rgba(255, 255, 255, 1);
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(30, 64, 175, 0.1);
    }
    
    .participant-icon {
        width: 40px;
        height: 40px;
        background: #1e40af;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        font-size: 1rem;
        color: white;
    }
    
    .participant-details {
        flex: 1;
    }
    
    .participant-label {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 4px;
    }
    
    .participant-value {
         font-size: 0.95rem;
         color: #1f2937;
         font-weight: 600;
         line-height: 1.3;
     }
     
     /* Event Information Styles */
     .event-info-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
         gap: 1rem;
         max-width: 100%;
     }
     
     .event-info-vertical {
         display: flex;
         flex-direction: column;
         gap: 0.75rem;
     }
     
     .event-item {
         display: flex;
         align-items: center;
         padding: 16px;
         background: rgba(255, 255, 255, 0.95);
         border: 1px solid rgba(30, 64, 175, 0.2);
         border-radius: 10px;
         margin-bottom: 12px;
         transition: all 0.3s ease;
     }
     
     .event-item:hover {
         background: rgba(255, 255, 255, 1);
         transform: translateX(5px);
     }
     
     .event-icon {
         width: 40px;
         height: 40px;
         background: #1e40af;
         border-radius: 8px;
         display: flex;
         align-items: center;
         justify-content: center;
         margin-right: 16px;
         font-size: 1rem;
         color: white;
     }
     
     .event-details {
         flex: 1;
     }
     
     .event-label {
         font-size: 0.8rem;
         color: #6b7280;
         font-weight: 500;
         text-transform: uppercase;
         letter-spacing: 0.3px;
         margin-bottom: 4px;
     }
     
     .event-value {
         font-size: 0.95rem;
         color: #1f2937;
         font-weight: 600;
         line-height: 1.3;
     }
     
     .price-highlight {
         color: #059669 !important;
         font-size: 1.1rem !important;
         font-weight: 700 !important;
     }
     
     /* Form Data Styles */
     .form-data-horizontal {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
         gap: 1rem;
         max-width: 100%;
     }
     
     .form-data-vertical {
         display: flex;
         flex-direction: column;
         gap: 0.75rem;
     }
     
     .form-data-item-horizontal {
         display: flex;
         align-items: center;
         gap: 0.75rem;
         padding: 0.75rem 1rem;
         background: rgba(255, 255, 255, 0.05);
         border-radius: 8px;
         border: 1px solid rgba(255, 255, 255, 0.1);
         transition: all 0.3s ease;
         min-width: 0;
         overflow: hidden;
     }
     
     .form-data-item-horizontal:hover {
         background: rgba(255, 255, 255, 0.08);
         transform: translateY(-1px);
     }
     
     .form-data-item-horizontal .form-data-icon {
          width: 32px;
          height: 32px;
          background: linear-gradient(135deg, #4fc3f7, #29b6f6);
          border-radius: 8px;
          display: flex;
          align-items: center;
          justify-content: center;
          color: white;
          font-size: 0.9rem;
          flex-shrink: 0;
      }
     
     .form-data-content {
         flex: 1;
         display: flex;
         flex-direction: column;
         gap: 0.25rem;
         min-width: 0;
         overflow: hidden;
     }
     
     .form-data-label-inline {
         font-size: 0.8rem;
         color: #adb5bd;
         font-weight: 500;
         text-transform: uppercase;
         letter-spacing: 0.3px;
     }
     
     .form-data-value-inline {
         color: white;
         font-weight: 500;
         font-size: 0.9rem;
         flex: 1;
         min-width: 0;
         overflow: hidden;
         text-overflow: ellipsis;
         white-space: nowrap;
     }
     
     .form-data-value {
         font-size: 0.95rem;
         color: white;
         font-weight: 600;
         line-height: 1.4;
         word-wrap: break-word;
     }
     
     .file-link {
         color: #17a2b8;
         text-decoration: none;
         font-weight: 600;
         transition: all 0.3s ease;
         display: inline-flex;
         align-items: center;
     }
     
     .file-link:hover {
         color: #20c997;
         text-decoration: none;
         transform: translateX(3px);
     }
     
     /* Payment Information Styles */
     .payment-info {
         display: flex;
         flex-direction: column;
         gap: 1.5rem;
     }
     
     .payment-item {
         display: flex;
         align-items: flex-start;
         gap: 1rem;
         padding: 1rem;
         background: rgba(255, 255, 255, 0.95);
         border-radius: 12px;
         border: 1px solid rgba(30, 64, 175, 0.2);
         transition: all 0.3s ease;
     }
     
     .payment-item:hover {
         background: rgba(255, 255, 255, 1);
         border-color: rgba(30, 64, 175, 0.3);
     }
     
     .payment-icon {
         width: 40px;
         height: 40px;
         background: #1e40af;
         border-radius: 10px;
         display: flex;
         align-items: center;
         justify-content: center;
         color: white;
         font-size: 1.1rem;
         flex-shrink: 0;
     }
     
     .payment-details {
         flex: 1;
     }
     
     .payment-label {
         color: #6b7280;
         font-size: 0.85rem;
         margin-bottom: 0.25rem;
         text-transform: uppercase;
         letter-spacing: 0.5px;
     }
     
     .payment-value {
          color: #1f2937;
          font-weight: 500;
          font-size: 1rem;
      }
      
      .price-highlight {
          color: #059669;
          font-weight: 600;
          font-size: 1.1rem;
      }
      
      .payment-badge {
          display: inline-flex;
          align-items: center;
          padding: 0.4rem 0.8rem;
          border-radius: 20px;
          font-size: 0.85rem;
          font-weight: 500;
          text-transform: uppercase;
          letter-spacing: 0.5px;
      }
      
      .payment-verified {
          background: rgba(76, 175, 80, 0.2);
          color: #4caf50;
          border: 1px solid rgba(76, 175, 80, 0.3);
      }
      
      .payment-pending {
          background: rgba(255, 193, 7, 0.2);
          color: #ffc107;
          border: 1px solid rgba(255, 193, 7, 0.3);
      }
      
      .payment-rejected {
          background: rgba(244, 67, 54, 0.2);
          color: #f44336;
          border: 1px solid rgba(244, 67, 54, 0.3);
      }
      
      .payment-none {
          background: rgba(158, 158, 158, 0.2);
          color: #9e9e9e;
          border: 1px solid rgba(158, 158, 158, 0.3);
      }
      
      .proof-link {
         color: #1e40af;
         text-decoration: none;
         font-weight: 500;
         transition: all 0.3s ease;
     }
     
     .proof-link:hover {
         color: #1d4ed8;
         text-decoration: underline;
     }
     
     .payment-notes {
          background: rgba(30, 64, 175, 0.1);
          border: 1px solid rgba(30, 64, 175, 0.2);
          border-radius: 12px;
          padding: 1rem;
          margin-top: 1rem;
      }
      
      .notes-header {
          display: flex;
          align-items: center;
          margin-bottom: 0.5rem;
      }
      
      .notes-label {
          color: #1e40af;
          font-weight: 500;
          font-size: 0.9rem;
      }
      
      .notes-content {
          color: #1f2937;
          line-height: 1.5;
      }
      
      .payment-actions {
         border-top: 1px solid rgba(30, 64, 175, 0.2);
         padding-top: 1rem;
     }
     
     /* Actions Grid Styles */
     .actions-grid {
         display: flex;
         flex-direction: column;
         gap: 2rem;
     }
     
     .action-group {
         background: rgba(255, 255, 255, 0.95);
         border-radius: 12px;
         padding: 1.5rem;
         border: 1px solid rgba(30, 64, 175, 0.2);
     }
     
     .action-group-title {
         color: #1e40af;
         font-weight: 600;
         font-size: 1rem;
         margin-bottom: 1rem;
         display: flex;
         align-items: center;
     }
     
     .action-buttons {
         display: flex;
         flex-direction: column;
         gap: 0.75rem;
     }
     
     .action-status-btn {
         background: rgba(30, 64, 175, 0.1);
         border: 1px solid rgba(30, 64, 175, 0.2);
         color: #1f2937;
         padding: 0.75rem 1rem;
         border-radius: 8px;
         font-weight: 500;
         transition: all 0.3s ease;
         cursor: pointer;
         display: flex;
         align-items: center;
     }
     
     .action-status-btn:hover {
         background: rgba(30, 64, 175, 0.15);
         border-color: rgba(30, 64, 175, 0.3);
         transform: translateY(-1px);
     }
     
     .action-status-btn.status-confirmed:hover {
         background: rgba(76, 175, 80, 0.2);
         border-color: #4caf50;
         color: #4caf50;
     }
     
     .action-status-btn.status-pending:hover {
         background: rgba(255, 193, 7, 0.2);
         border-color: #ffc107;
         color: #ffc107;
     }
     
     .action-status-btn.status-cancelled:hover {
         background: rgba(244, 67, 54, 0.2);
         border-color: #f44336;
         color: #f44336;
     }
     
     .action-danger-btn {
         background: rgba(244, 67, 54, 0.1);
         border: 1px solid rgba(244, 67, 54, 0.3);
         color: #f44336;
         padding: 0.75rem 1rem;
         border-radius: 8px;
         font-weight: 500;
         transition: all 0.3s ease;
         cursor: pointer;
         display: flex;
         align-items: center;
     }
     
     .action-danger-btn:hover {
         background: rgba(244, 67, 54, 0.2);
         border-color: #f44336;
         transform: translateY(-1px);
     }
     
     /* Payment Proof Image Styles */
     .payment-proof-preview {
         background: rgba(255, 255, 255, 0.95);
         border-radius: 12px;
         padding: 1rem;
         border: 1px solid rgba(30, 64, 175, 0.2);
     }
     
     .proof-image-container {
         position: relative;
         border-radius: 8px;
         overflow: hidden;
         background: rgba(0, 0, 0, 0.3);
     }
     
     .proof-image {
         width: 100%;
         height: auto;
         max-height: 300px;
         object-fit: contain;
         cursor: pointer;
         transition: all 0.3s ease;
         border-radius: 8px;
     }
     
     .proof-image:hover {
         transform: scale(1.02);
         box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
     }
     
     .proof-image-error {
         display: flex;
         flex-direction: column;
         align-items: center;
         justify-content: center;
         padding: 2rem;
         color: #adb5bd;
         text-align: center;
     }
     
     .proof-image-error i {
         font-size: 2rem;
         margin-bottom: 0.5rem;
         color: #1e40af;
     }
     
     /* Image Modal Styles */
     .image-modal {
         display: none;
         position: fixed;
         z-index: 9999;
         left: 0;
         top: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.9);
         backdrop-filter: blur(5px);
     }
     
     .image-modal-content {
         position: relative;
         margin: auto;
         display: block;
         width: 90%;
         max-width: 800px;
         max-height: 90%;
         object-fit: contain;
         top: 50%;
         transform: translateY(-50%);
         border-radius: 8px;
     }
     
     .image-modal-close {
         position: absolute;
         top: 20px;
         right: 35px;
         color: #fff;
         font-size: 40px;
         font-weight: bold;
         cursor: pointer;
         z-index: 10000;
         transition: all 0.3s ease;
     }
     
     .image-modal-close:hover {
         color: #1e40af;
         transform: scale(1.1);
     }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-white mb-0">
                    <i class="fas fa-user-check me-2"></i>
                    Registration Details
                </h2>
                <a href="{{ route('admin.registrations.index') }}" class="btn action-btn btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Back to Registrations
                </a>
            </div>
        </div>
    </div>
    
    <!-- Authentication Status Alert -->
    <div class="row mb-3">
        <div class="col-12">
            <div id="auth-alert" class="alert alert-warning d-none" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Authentication Required:</strong> Please make sure you are logged in to use payment update features.
                <a href="/login" class="alert-link ms-2">Login here</a>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-lg-6">
            <!-- Registration Summary Card -->
            <div class="registration-detail-card p-4 mb-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="icon-wrapper me-3">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h4 class="text-white mb-1">Registration Information</h4>
                        <p class="text-white mb-0">Complete registration details and status</p>
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-header">
                                <i class="fas fa-hashtag text-primary"></i>
                                <span class="info-label">Registration ID</span>
                            </div>
                            <div class="info-value">#{{ $registration->id }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-header">
                                <i class="fas fa-calendar text-info"></i>
                                <span class="info-label">Registration Date</span>
                            </div>
                            <div class="info-value">
                                @if($registration->registered_at)
                                    {{ $registration->registered_at->format('M d, Y H:i') }}
                                @else
                                    <span class="text-muted">Date not set</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-header">
                                <i class="fas fa-flag text-warning"></i>
                                <span class="info-label">Registration Status</span>
                            </div>
                            <div class="info-value">
                                <span class="status-badge status-{{ $registration->status }}">
                                    <i class="fas fa-circle me-1"></i>
                                    {{ ucfirst($registration->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-header">
                                <i class="fas fa-credit-card text-success"></i>
                                <span class="info-label">Payment Status</span>
                            </div>
                            <div class="info-value">
                                @if($registration->payment && $registration->payment->payment_status)
                                    <span class="payment-badge payment-{{ $registration->payment->payment_status }}">
                                        <i class="fas fa-circle me-1"></i>
                                        {{ ucfirst($registration->payment->payment_status) }}
                                    </span>
                                @else
                                    <span class="payment-badge payment-none">
                                        <i class="fas fa-circle me-1"></i>
                                        Not Set
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($registration->notes)
                <div class="mt-4">
                    <div class="notes-section">
                        <div class="notes-header">
                            <i class="fas fa-sticky-note text-warning me-2"></i>
                            <span class="notes-label">Notes</span>
                        </div>
                        <div class="notes-content">{{ $registration->notes }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="col-lg-6">
            <!-- Participant Information -->
            <div class="registration-detail-card p-4 mb-4">
                <div class="card-header-custom mb-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-wrapper me-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-1">Participant</h5>
                            <p class="text-white mb-0 small">Registered user information</p>
                        </div>
                    </div>
                </div>
                
                <div class="participant-info">
                    <div class="participant-item">
                        <div class="participant-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="participant-details">
                            <div class="participant-label">Full Name</div>
                            <div class="participant-value">{{ $registration->user->name }}</div>
                        </div>
                    </div>
                    
                    <div class="participant-item">
                        <div class="participant-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="participant-details">
                            <div class="participant-label">Email Address</div>
                            <div class="participant-value">{{ $registration->user->email }}</div>
                        </div>
                    </div>
                    
                    @if($registration->user->phone)
                    <div class="participant-item">
                        <div class="participant-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="participant-details">
                            <div class="participant-label">Phone Number</div>
                            <div class="participant-value">{{ $registration->user->phone }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    
    <div class="row g-4 mt-2">
        <div class="col-lg-4">
            <!-- Event Information -->
            <div class="registration-detail-card p-4 mb-4">
                <div class="card-header-custom mb-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-wrapper me-3">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-1">Event Details</h5>
                            <p class="text-white mb-0 small">Information about the registered event</p>
                        </div>
                    </div>
                </div>
                
                <div class="event-info-vertical">
                    <div class="event-item">
                        <div class="event-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="event-details">
                            <div class="event-label">Event Title</div>
                            <div class="event-value">{{ $registration->event->title }}</div>
                        </div>
                    </div>
                    
                    <div class="event-item">
                        <div class="event-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="event-details">
                            <div class="event-label">Event Date & Time</div>
                            <div class="event-value">
                                @if($registration->event->event_date)
                                    {{ $registration->event->event_date->format('M d, Y H:i') }}
                                @else
                                    <span class="text-muted">Date not set</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="event-item">
                        <div class="event-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="event-details">
                            <div class="event-label">Location</div>
                            <div class="event-value">{{ $registration->event->location }}</div>
                        </div>
                    </div>
                    
                    @if($registration->event->price)
                    <div class="event-item">
                        <div class="event-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="event-details">
                            <div class="event-label">Registration Fee</div>
                            <div class="event-value price-highlight">Rp {{ number_format($registration->event->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
        </div>
        
        <div class="col-lg-4">
            <!-- Registration Form Data -->
            @if($registration->registrationData && $registration->registrationData->count() > 0)
            <div class="registration-detail-card p-4 mb-4">
                <div class="card-header-custom mb-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-wrapper me-3">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-1">Registration Form Data</h5>
                            <p class="text-white mb-0 small">Additional information provided during registration</p>
                        </div>
                    </div>
                </div>
                
                <div class="form-data-vertical">
                    @foreach($registration->registrationData as $data)
                    <div class="form-data-item-horizontal">
                        <div class="form-data-icon">
                            @if($data->field_type === 'file')
                                <i class="fas fa-file-alt"></i>
                            @elseif($data->field_type === 'email')
                                <i class="fas fa-envelope"></i>
                            @elseif($data->field_type === 'tel')
                                <i class="fas fa-phone"></i>
                            @else
                                <i class="fas fa-edit"></i>
                            @endif
                        </div>
                        <div class="form-data-content">
                            <div class="form-data-label-inline">{{ $data->field_label }}</div>
                            <div class="form-data-value-inline">
                                @if($data->field_type === 'file' && $data->field_value)
                                    <a href="{{ Storage::url($data->field_value) }}" target="_blank" class="file-link">
                                        <i class="fas fa-download me-2"></i>View File
                                    </a>
                                @else
                                    {{ $data->field_value ?: 'Not provided' }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
        </div>
        
        <div class="col-lg-4">
            <!-- Payment Information -->
            @if($registration->event->price > 0)
            <div class="registration-detail-card p-4 mb-4">
                <div class="card-header-custom mb-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-wrapper me-3">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-1">Payment Information</h5>
                            <p class="text-white mb-0 small">Payment details and verification status</p>
                        </div>
                    </div>
                </div>
                
                <div class="payment-info">
                    <div class="payment-item">
                        <div class="payment-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="payment-details">
                            <div class="payment-label">Payment Amount</div>
                            <div class="payment-value price-highlight">Rp {{ number_format($registration->event->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    
                    @if($registration->payment)
                        <div class="payment-item">
                            <div class="payment-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="payment-details">
                                <div class="payment-label">Payment Method</div>
                                <div class="payment-value">
                                    @switch($registration->payment->payment_method)
                                        @case('bank_transfer')
                                            <span class="method-badge"><i class="fas fa-university me-1"></i>Bank Transfer</span>
                                            @break
                                        @case('e_wallet')
                                            <span class="method-badge"><i class="fas fa-mobile-alt me-1"></i>E-Wallet</span>
                                            @break
                                        @case('qris')
                                            <span class="method-badge"><i class="fas fa-qrcode me-1"></i>QRIS</span>
                                            @break
                                        @case('cash')
                                            <span class="method-badge"><i class="fas fa-money-bill me-1"></i>Cash Payment</span>
                                            @break
                                        @default
                                            <span class="method-badge"><i class="fas fa-question me-1"></i>{{ ucfirst(str_replace('_', ' ', $registration->payment->payment_method)) }}</span>
                                    @endswitch
                                </div>
                            </div>
                        </div>
                        <div class="payment-item">
                            <div class="payment-icon">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                            <div class="payment-details">
                                <div class="payment-label">Payment Status</div>
                                <div class="payment-value">
                                    <span class="payment-badge payment-{{ $registration->payment->payment_status }}">
                                        <i class="fas fa-circle me-1"></i>
                                        {{ ucfirst($registration->payment->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if($registration->payment->payment_proof)
                        <div class="payment-item">
                            <div class="payment-icon">
                                <i class="fas fa-file-image"></i>
                            </div>
                            <div class="payment-details">
                                <div class="payment-label">Proof of Payment</div>
                                <div class="payment-value">
                                    <a href="{{ Storage::disk('public_uploads')->url($registration->payment->payment_proof) }}" target="_blank" class="proof-link">
                                        <i class="fas fa-external-link-alt me-2"></i>View Payment Proof
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Proof Image Preview -->
                        <div class="payment-proof-preview mt-3">
                            <div class="proof-image-container">
                                <img src="{{ Storage::disk('public_uploads')->url($registration->payment->payment_proof) }}" 
                                     alt="Payment Proof" 
                                     class="proof-image"
                                     onclick="openImageModal({{ json_encode(Storage::disk('public_uploads')->url($registration->payment->payment_proof)) }})"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                                <div class="proof-image-error" style="display: none;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Unable to load image preview</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="payment-item">
                            <div class="payment-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="payment-details">
                                <div class="payment-label">Payment Date</div>
                                <div class="payment-value">{{ $registration->payment->created_at->format('M d, Y H:i') }}</div>
                            </div>
                        </div>
                        
                        @if($registration->payment->notes)
                        <div class="payment-notes">
                            <div class="notes-header">
                                <i class="fas fa-sticky-note text-info me-2"></i>
                                <span class="notes-label">Payment Notes</span>
                            </div>
                            <div class="notes-content">{{ $registration->payment->notes }}</div>
                        </div>
                        @endif
                        
                        <!-- Payment Actions -->
                        @if($registration->payment->payment_status === 'pending')
                        <div class="payment-actions mt-4">
                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.payments.updateStatus', $registration->payment->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="verified">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to verify this payment?')">
                                        <i class="fas fa-check me-2"></i>Verify Payment
                                    </button>
                                </form>
                                <form action="{{ route('admin.payments.updateStatus', $registration->payment->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this payment?')">
                                        <i class="fas fa-times me-2"></i>Reject Payment
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="payment-item">
                            <div class="payment-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="payment-details">
                                <div class="payment-label">Payment Status</div>
                                <div class="payment-value">
                                    <span class="payment-badge payment-none">
                                        <i class="fas fa-circle me-1"></i>
                                        No Payment Submitted
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
            
           
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
    <img class="image-modal-content" id="modalImage">
</div>

@endsection

@push('scripts')
<script>
    // Image modal functions
    function openImageModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.style.display = 'block';
        modalImg.src = imageSrc;
    }
    
    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }
    
    // Close modal when clicking outside the image
    window.onclick = function(event) {
        const modal = document.getElementById('imageModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
    
    // Session keep-alive (ping every 5 minutes)
    setInterval(function() {
        fetch('/admin/ping', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).catch(function(error) {
            console.log('Keep-alive ping failed:', error);
        });
    }, 300000); // 5 minutes
</script>
@endpush