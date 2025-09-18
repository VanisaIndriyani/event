@extends('layouts.app')

@section('title', 'Collaboration - Epic Events')

@push('styles')
<style>
    .collaboration-container {
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
        color: var(--text-gray);
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }
    
    .collaboration-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    
    .collaboration-info {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2.5rem;
    }
    
    .info-title {
        font-family: 'Orbitron', monospace;
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-title i {
        color: var(--accent-blue);
    }
    
    .collaboration-types {
        margin-bottom: 2rem;
    }
    
    .type-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .type-item:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }
    
    .type-icon {
        width: 50px;
        height: 50px;
        background: var(--gradient-accent);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    
    .type-content h4 {
        color: var(--text-light);
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .type-content p {
        color: var(--text-gray);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 0;
    }
    
    .benefits-section {
        margin-bottom: 2rem;
    }
    
    .benefits-title {
        color: var(--text-light);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }
    
    .benefits-list {
        list-style: none;
        padding: 0;
    }
    
    .benefits-list li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        color: var(--text-gray);
        font-size: 0.9rem;
    }
    
    .benefits-list li i {
        color: var(--accent-blue);
        font-size: 1rem;
    }
    
    .collaboration-form {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2.5rem;
    }
    
    .form-title {
        font-family: 'Orbitron', monospace;
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-title i {
        color: var(--accent-blue);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-light);
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        color: #ffffff;
        font-size: 1rem;
        transition: all 0.3s ease;
        resize: vertical;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
    }
    
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .form-control.textarea {
        min-height: 120px;
        font-family: inherit;
    }
    
    .form-select {
        appearance: none;
        background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="%23ffffff" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 12px;
        padding-right: 40px;
        color: #ffffff;
    }
    
    .form-select option {
        background: #1a1a2e;
        color: #ffffff;
        padding: 8px;
    }
    
    .checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 0.5rem;
    }
    
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--accent-blue);
    }
    
    .checkbox-item label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        cursor: pointer;
        margin-bottom: 0;
    }
    
    .btn-submit {
        width: 100%;
        padding: 14px;
        background: var(--gradient-accent);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 1rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(59, 130, 246, 0.3);
    }
    
    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .success-message {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10B981;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        display: none;
    }
    
    .success-message.show {
        display: block;
    }
    
    .error-message {
        color: #EF4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    
    .contact-info {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
    }
    
    .contact-title {
        font-family: 'Orbitron', monospace;
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 1rem;
    }
    
    .contact-methods {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }
    
    .contact-method {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        color: var(--text-gray);
        transition: all 0.3s ease;
        padding: 1rem;
        border-radius: 12px;
    }
    
    .contact-method:hover {
        color: var(--accent-blue);
        background: rgba(59, 130, 246, 0.1);
        text-decoration: none;
        transform: translateY(-2px);
    }
    
    .contact-method i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .contact-method span {
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 2.5rem;
        }
        
        .collaboration-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .checkbox-group {
            grid-template-columns: 1fr;
        }
        
        .contact-methods {
            flex-direction: column;
            gap: 1rem;
        }
        
        .collaboration-info,
        .collaboration-form {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="collaboration-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Let's Collaborate</h1>
            <p class="page-subtitle">
                Partner with Epic Events to create extraordinary experiences. Whether you're a brand, organization, or individual with a vision, we're here to make it epic together.
            </p>
        </div>
        
        <!-- Main Content -->
        <div class="collaboration-content">
            <!-- Collaboration Info -->
            <div class="collaboration-info">
                <h2 class="info-title">
                    <i class="fas fa-handshake"></i>
                    Collaboration Types
                </h2>
                
                <div class="collaboration-types">
                    <div class="type-item">
                        <div class="type-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="type-content">
                            <h4>Corporate Events</h4>
                            <p>Team building, product launches, conferences, and corporate celebrations tailored to your brand.</p>
                        </div>
                    </div>
                    
                    <div class="type-item">
                        <div class="type-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="type-content">
                            <h4>Brand Partnerships</h4>
                            <p>Sponsorship opportunities, brand activations, and co-marketing initiatives for maximum impact.</p>
                        </div>
                    </div>
                    
                    <div class="type-item">
                        <div class="type-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="type-content">
                            <h4>Community Events</h4>
                            <p>Social impact events, charity fundraisers, and community engagement programs.</p>
                        </div>
                    </div>
                    
                    <div class="type-item">
                        <div class="type-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div class="type-content">
                            <h4>Custom Experiences</h4>
                            <p>Unique, tailor-made events designed specifically for your vision and objectives.</p>
                        </div>
                    </div>
                </div>
                
                <div class="benefits-section">
                    <h3 class="benefits-title">Why Partner With Us?</h3>
                    <ul class="benefits-list">
                        <li><i class="fas fa-check-circle"></i> 3+ years of event management experience</li>
                        <li><i class="fas fa-check-circle"></i> Professional team of creative experts</li>
                        <li><i class="fas fa-check-circle"></i> End-to-end event planning and execution</li>
                        <li><i class="fas fa-check-circle"></i> Extensive network of vendors and venues</li>
                        <li><i class="fas fa-check-circle"></i> Comprehensive marketing and promotion</li>
                        <li><i class="fas fa-check-circle"></i> Post-event analytics and reporting</li>
                        <li><i class="fas fa-check-circle"></i> Flexible packages to fit your budget</li>
                    </ul>
                </div>
            </div>
            
            <!-- Collaboration Form -->
            <div class="collaboration-form">
                <h2 class="form-title">
                    <i class="fas fa-paper-plane"></i>
                    Start Your Collaboration
                </h2>
                
                <div class="success-message" id="successMessage">
                    <i class="fas fa-check-circle me-2"></i>
                    Thank you for your interest! We'll get back to you within 24 hours.
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success mb-3">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                <form id="collaborationForm" action="{{ route('collab.submit') }}" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="organization" class="form-label">Organization</label>
                            <input type="text" id="organization" name="organization" class="form-control" placeholder="Company/Organization name">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" class="form-control" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="collaboration_type" class="form-label">Collaboration Type *</label>
                        <select id="collaboration_type" name="collaboration_type" class="form-control form-select" required>
                            <option value="">Select collaboration type</option>
                            <option value="corporate">Corporate Events</option>
                            <option value="brand">Brand Partnership</option>
                            <option value="community">Community Events</option>
                            <option value="custom">Custom Experience</option>
                            <option value="sponsorship">Sponsorship Opportunity</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="budget" class="form-label">Estimated Budget</label>
                        <select id="budget" name="budget" class="form-control form-select">
                            <option value="">Select budget range</option>
                            <option value="under-10m">Under Rp 10 Million</option>
                            <option value="10m-25m">Rp 10 - 25 Million</option>
                            <option value="25m-50m">Rp 25 - 50 Million</option>
                            <option value="50m-100m">Rp 50 - 100 Million</option>
                            <option value="over-100m">Over Rp 100 Million</option>
                            <option value="discuss">Let's discuss</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="timeline" class="form-label">Preferred Timeline</label>
                        <select id="timeline" name="timeline" class="form-control form-select">
                            <option value="">Select timeline</option>
                            <option value="asap">ASAP (Within 1 month)</option>
                            <option value="1-3months">1-3 months</option>
                            <option value="3-6months">3-6 months</option>
                            <option value="6months+">6+ months</option>
                            <option value="flexible">Flexible</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Services Needed</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="planning" name="services[]" value="planning">
                                <label for="planning">Event Planning</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="marketing" name="services[]" value="marketing">
                                <label for="marketing">Marketing & Promotion</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="venue" name="services[]" value="venue">
                                <label for="venue">Venue Management</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="catering" name="services[]" value="catering">
                                <label for="catering">Catering Services</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="entertainment" name="services[]" value="entertainment">
                                <label for="entertainment">Entertainment</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="photography" name="services[]" value="photography">
                                <label for="photography">Photography/Videography</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="event_description" class="form-label">Project Description *</label>
                        <textarea id="event_description" name="event_description" class="form-control textarea" placeholder="Tell us about your vision, goals, and any specific requirements..." required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="additional_info" class="form-label">Additional Information</label>
                        <textarea id="additional_info" name="additional_info" class="form-control textarea" placeholder="Any other details you'd like to share..."></textarea>
                    </div>
                    
                    @csrf
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-rocket me-2"></i>Send Collaboration Request
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Contact Info -->
        <div class="contact-info">
            <h3 class="contact-title">Prefer Direct Contact?</h3>
            <div class="contact-methods">
                <a href="mailto:collaborate@epicevents.com" class="contact-method">
                    <i class="fas fa-envelope"></i>
                    <span>collaborate@epicevents.com</span>
                </a>
                <a href="https://wa.me/6281234567890" class="contact-method" target="_blank">
                    <i class="fab fa-whatsapp"></i>
                    <span>+62 812-3456-7890</span>
                </a>
                <a href="tel:+6281234567890" class="contact-method">
                    <i class="fas fa-phone"></i>
                    <span>+62 812-3456-7890</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('collaborationForm');
    const successMessage = document.getElementById('successMessage');
    const submitButton = form.querySelector('.btn-submit');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Disable submit button
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
        
        // Simulate form submission
        setTimeout(() => {
            // Show success message
            successMessage.classList.add('show');
            
            // Reset form
            form.reset();
            
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-rocket me-2"></i>Send Collaboration Request';
            
            // Hide success message after 5 seconds
            setTimeout(() => {
                successMessage.classList.remove('show');
            }, 5000);
            
            // Scroll to success message
            successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 2000);
    });
    
    // Form validation
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            validateField(this);
        });
    });
    
    function validateField(field) {
        const errorElement = field.parentNode.querySelector('.error-message');
        
        if (field.value.trim() === '') {
            if (!errorElement) {
                const error = document.createElement('div');
                error.className = 'error-message';
                error.textContent = 'This field is required';
                field.parentNode.appendChild(error);
            }
            field.style.borderColor = '#EF4444';
        } else {
            if (errorElement) {
                errorElement.remove();
            }
            field.style.borderColor = 'rgba(255, 255, 255, 0.2)';
        }
    }
});
</script>
@endsection