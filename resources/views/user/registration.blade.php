@extends('layouts.app')

@section('title', 'Registration - ' . $event->title)

@push('styles')
<style>
    .registration-container {
        padding: 2rem 0;
        min-height: calc(100vh - 80px);
    }
    
    .registration-header {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .event-title {
        font-family: 'Orbitron', monospace;
        font-size: 2rem;
        font-weight: 700;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }
    
    .event-info {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
    }
    
    .registration-form {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2rem;
    }
    
    .form-section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        color: white;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control, .form-select {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        color: #1f2937;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        background: rgba(255, 255, 255, 1);
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        color: #1f2937;
    }
    
    .form-control::placeholder {
        color: rgba(0, 0, 0, 0.5);
    }
    
    .form-text {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .btn-submit {
        background: var(--gradient-accent);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        padding: 15px 30px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 1rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        color: white;
    }
    
    .text-muted {
        color: white !important;
    }
    
    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        color: var(--text-light);
        padding: 10px 20px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }
    
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        text-decoration: none;
    }
    
    .email-info {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .email-info h6 {
        color: #3b82f6;
        margin-bottom: 1rem;
    }
    
    .email-info p {
        color: var(--text-gray);
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="container registration-container">
    <a href="{{ route('events') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i>
        Back to Events
    </a>
    
    <div class="registration-header">
        <h1 class="event-title">{{ $event->title }}</h1>
        <div class="event-info">
            <div class="info-item">
                <i class="fas fa-calendar"></i>
                <span>{{ $event->event_date ? $event->event_date->format('M d, Y') : 'TBA' }}</span>
            </div>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $event->location }}</span>
            </div>
            <div class="info-item">
                <i class="fas fa-tag"></i>
                <span>{{ $event->price == 0 || !$event->price ? 'Free' : 'Rp ' . number_format($event->price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 15px; color: #22c55e;">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 15px; color: #ef4444;">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
        </div>
    @endif
    
    <div class="email-info">
                    <h6><i class="fas fa-envelope me-2"></i>Email Notification</h6>
                    <p>Setelah Anda mengirim formulir pendaftaran, notifikasi otomatis akan dikirim ke email Anda dengan detail pendaftaran dan instruksi pembayaran (jika diperlukan).</p>
                </div>
    
    <div class="registration-form">
        <form action="{{ route('event.register', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <h5 class="form-section-title">
                <i class="fas fa-user-edit"></i>
                Registration Information
            </h5>
            
            @if($event->activeFormFields && $event->activeFormFields->count() > 0)
                @foreach($event->activeFormFields as $field)
                    <div class="form-group">
                        <label for="form_{{ $field->field_name }}" class="form-label">
                            {{ $field->field_label }}
                            @if($field->is_required)
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        
                        @if($field->field_type === 'text')
                            <input type="text" 
                                   class="form-control @error('form_' . $field->field_name) is-invalid @enderror" 
                                   id="form_{{ $field->field_name }}" 
                                   name="form_{{ $field->field_name }}" 
                                   value="{{ old('form_' . $field->field_name) }}"
                                   placeholder="Enter {{ strtolower($field->field_label) }}"
                                   {{ $field->is_required ? 'required' : '' }}>
                        
                        @elseif($field->field_type === 'email')
                            <input type="email" 
                                   class="form-control @error('form_' . $field->field_name) is-invalid @enderror" 
                                   id="form_{{ $field->field_name }}" 
                                   name="form_{{ $field->field_name }}" 
                                   value="{{ old('form_' . $field->field_name) }}"
                                   placeholder="Enter email address"
                                   {{ $field->is_required ? 'required' : '' }}>
                        
                        @elseif($field->field_type === 'number')
                            <input type="number" 
                                   class="form-control @error('form_' . $field->field_name) is-invalid @enderror" 
                                   id="form_{{ $field->field_name }}" 
                                   name="form_{{ $field->field_name }}" 
                                   value="{{ old('form_' . $field->field_name) }}"
                                   placeholder="Enter {{ strtolower($field->field_label) }}"
                                   {{ $field->is_required ? 'required' : '' }}>
                        
                        @elseif($field->field_type === 'textarea')
                            <textarea class="form-control @error('form_' . $field->field_name) is-invalid @enderror" 
                                      id="form_{{ $field->field_name }}" 
                                      name="form_{{ $field->field_name }}" 
                                      rows="4"
                                      placeholder="Enter {{ strtolower($field->field_label) }}"
                                      {{ $field->is_required ? 'required' : '' }}>{{ old('form_' . $field->field_name) }}</textarea>
                        
                        @elseif($field->field_type === 'select')
                            <select class="form-select @error('form_' . $field->field_name) is-invalid @enderror" 
                                    id="form_{{ $field->field_name }}" 
                                    name="form_{{ $field->field_name }}"
                                    {{ $field->is_required ? 'required' : '' }}>
                                <option value="">Pilih {{ $field->field_label }}</option>
                                @if($field->field_options)
                                    @foreach($field->field_options as $option)
                                        <option value="{{ $option }}" 
                                            {{ old('form_' . $field->field_name) == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        
                        @elseif($field->field_type === 'file')
                            <input type="file" 
                                   class="form-control @error('form_' . $field->field_name) is-invalid @enderror" 
                                   id="form_{{ $field->field_name }}" 
                                   name="form_{{ $field->field_name }}"
                                   {{ $field->is_required ? 'required' : '' }}>
                            <div class="form-text">Max file size: 2MB</div>
                        
                        @elseif($field->field_type === 'date')
                            <input type="date" 
                                   class="form-control @error('form_' . $field->field_name) is-invalid @enderror" 
                                   id="form_{{ $field->field_name }}" 
                                   name="form_{{ $field->field_name }}" 
                                   value="{{ old('form_' . $field->field_name) }}"
                                   {{ $field->is_required ? 'required' : '' }}>
                        @endif
                        
                        @error('form_' . $field->field_name)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No additional information required for this event.
                </div>
            @endif
            
            <!-- Payment Section for Paid Events -->
            @if($event->price > 0)
                <h5 class="form-section-title mt-4">
                    <i class="fas fa-credit-card"></i>
                    Payment Information
                </h5>
                
                <div class="alert alert-info mb-3" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 15px; color: #3b82f6;">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Event Fee: Rp {{ number_format($event->price, 0, ',', '.') }}</strong><br>
                    <small>Please select your payment method and upload payment proof after making the payment.</small>
                </div>
                
                <div class="form-group mb-3">
                    <label for="payment_method" class="form-label">
                        Payment Method <span class="text-danger">*</span>
                    </label>
                    <select class="form-control @error('payment_method') is-invalid @enderror" 
                            id="payment_method" 
                            name="payment_method"
                            required>
                        <option value="">Choose Payment Method</option>
                        @foreach($paymentSections as $index => $section)
                            @if($section['enabled'])
                                <option value="section_{{ $index }}">{{ $section['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Dynamic Payment Information -->
                @foreach($paymentSections as $index => $section)
                    @if($section['enabled'])
                        <div id="payment-info-{{ $index }}" class="alert mb-3" style="display: none; background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 15px; color: #3b82f6;">
                            <h6>
                                @if(stripos($section['name'], 'bank') !== false || stripos($section['name'], 'transfer') !== false)
                                    <i class="fas fa-university me-2"></i>
                                @elseif(stripos($section['name'], 'wallet') !== false || stripos($section['name'], 'ovo') !== false || stripos($section['name'], 'gopay') !== false || stripos($section['name'], 'dana') !== false)
                                    <i class="fas fa-mobile-alt me-2"></i>
                                @elseif(stripos($section['name'], 'qris') !== false || stripos($section['name'], 'qr') !== false)
                                    <i class="fas fa-qrcode me-2"></i>
                                @elseif(stripos($section['name'], 'cash') !== false || stripos($section['name'], 'tunai') !== false)
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                @else
                                    <i class="fas fa-credit-card me-2"></i>
                                @endif
                                {{ $section['name'] }} Information
                            </h6>
                            
                            @if(stripos($section['name'], 'qris') !== false || stripos($section['name'], 'qr') !== false)
                                <!-- QRIS Section -->
                                <div class="text-center">
                                    @if($qrCodeUrl)
                                        <div class="qr-code-container" style="margin: 0 auto; max-width: 250px;">
                                            <img src="{{ $qrCodeUrl }}" alt="QR Code" class="img-fluid" style="border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                                        </div>
                                    @else
                                        <div class="qr-placeholder" style="width: 200px; height: 200px; background: rgba(255,255,255,0.9); border: 2px dashed #3b82f6; border-radius: 10px; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: #1f2937;">
                                            <div>
                                                <i class="fas fa-qrcode" style="font-size: 3rem; margin-bottom: 10px;"></i><br>
                                                <strong>QR Code Here</strong>
                                            </div>
                                        </div>
                                    @endif
                                    <p class="mt-3 mb-0"><strong>Scan QR Code above with any QRIS-enabled app</strong></p>
                                </div>
                                <small class="mt-2 d-block"><i class="fas fa-info-circle me-1"></i>Scan the QR code, pay the exact amount, and upload the payment proof below.</small>
                            @else
                                <!-- Other Payment Methods -->
                                <div class="payment-details">
                                    @if($section['number'])
                                        <strong>{{ $section['name'] }}</strong><br>
                                        <span>Number/Account: {{ $section['number'] }}</span><br>
                                    @endif
                                    @if($section['account_name'])
                                        <span>Account Name: {{ $section['account_name'] }}</span>
                                    @endif
                                </div>
                                <small class="mt-2 d-block"><i class="fas fa-info-circle me-1"></i>Please transfer/send the exact amount and upload the payment proof below.</small>
                            @endif
                        </div>
                    @endif
                @endforeach
                
                <div class="form-group">
                    <label for="payment_proof" class="form-label">
                        Upload Payment Proof <span class="text-danger">*</span>
                    </label>
                    <input type="file" 
                           class="form-control @error('payment_proof') is-invalid @enderror" 
                           id="payment_proof" 
                           name="payment_proof"
                           accept=".jpg,.jpeg,.png,.pdf"
                           required>
                    <div class="form-text">Accepted formats: JPG, JPEG, PNG, PDF. Max size: 2MB</div>
                    @error('payment_proof')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif
            
            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-paper-plane me-2"></i>
                Submit Registration
            </button>
            
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>
                    Your information is secure and will only be used for event registration purposes.
                </small>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action*="register"]');
    const submitBtn = document.getElementById('submitBtn');
    const paymentMethodSelect = document.getElementById('payment_method');
    const bankInfo = document.getElementById('bank-info');
    
    // Handle payment method change
    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', function() {
            // Hide all payment info sections
            const paymentInfos = document.querySelectorAll('[id^="payment-info-"]');
            paymentInfos.forEach(info => {
                info.style.display = 'none';
            });
            
            // Show relevant payment info based on selection
            if (this.value.startsWith('section_')) {
                const sectionIndex = this.value.replace('section_', '');
                const paymentInfo = document.getElementById('payment-info-' + sectionIndex);
                if (paymentInfo) {
                    paymentInfo.style.display = 'block';
                }
            }
        });
    }
    
    if (form && submitBtn) {
        console.log('Form and submit button found');
        
        // Add form submit event listener only
        form.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            
            // Disable button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            
            // Let the form submit naturally
        });
        
        // Debug: Log when button is clicked
        submitBtn.addEventListener('click', function(e) {
            console.log('Submit button clicked - form should submit naturally');
        });
    } else {
        console.error('Form or submit button not found');
        console.log('Form:', form);
        console.log('Submit button:', submitBtn);
    }
});
</script>
@endpush