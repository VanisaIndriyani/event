@extends('layouts.app')

@section('title', 'Register - Epic Events')

@push('styles')
<style>
    .auth-container {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }
    
    .auth-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 3rem;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }
    
    .auth-logo-container {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .auth-logo {
              height: 150px;
              width: auto;
              filter: brightness(1.2) drop-shadow(0 0 20px rgba(59, 130, 246, 0.3));
              transition: all 0.3s ease;
          }
    
    .auth-logo:hover {
        transform: scale(1.05);
        filter: brightness(1.4) drop-shadow(0 0 30px rgba(59, 130, 246, 0.5));
    }
    
    .auth-title {
        font-family: 'Orbitron', monospace;
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 0.5rem;
        background: var(--gradient-accent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .auth-subtitle {
        text-align: center;
        color: var(--text-gray);
        margin-bottom: 2rem;
        font-size: 1rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-row {
        display: flex;
        gap: 1rem;
    }
    
    .form-row .form-group {
        flex: 1;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-light);
        font-weight: 500;
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
    
    .password-input-wrapper {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
        font-size: 1rem;
        transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
        color: #ffffff;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #ffffff;
        font-weight: 500;
    }
    
    .btn-auth {
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
        margin-bottom: 1rem;
    }
    
    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(59, 130, 246, 0.3);
    }
    
    .auth-link {
        text-align: center;
        margin-top: 1.5rem;
    }
    
    .auth-link a {
        color: var(--accent-blue);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .auth-link a:hover {
        color: var(--bright-blue);
        text-decoration: underline;
    }
    
    .error-message {
        color: #EF4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    
    .password-strength {
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }
    
    .strength-weak { color: #EF4444; }
    .strength-medium { color: #F59E0B; }
    .strength-strong { color: #10B981; }
    
    .floating-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        overflow: hidden;
    }
    
    .floating-shape {
        position: absolute;
        opacity: 0.1;
        animation: floatShape 8s ease-in-out infinite;
    }
    
    .floating-shape:nth-child(1) {
        top: 10%;
        left: 10%;
        width: 100px;
        height: 100px;
        background: var(--accent-blue);
        border-radius: 50%;
        animation-delay: 0s;
    }
    
    .floating-shape:nth-child(2) {
        top: 70%;
        right: 10%;
        width: 80px;
        height: 80px;
        background: var(--purple);
        border-radius: 20px;
        animation-delay: 3s;
    }
    
    .floating-shape:nth-child(3) {
        bottom: 20%;
        left: 20%;
        width: 60px;
        height: 60px;
        background: var(--cyan);
        border-radius: 50%;
        animation-delay: 6s;
    }
    
    @keyframes floatShape {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(180deg); }
    }
    
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }
        
        .auth-card {
            padding: 2rem;
            margin: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-container position-relative">
    <div class="floating-bg">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="auth-card">
                    <div class="auth-logo-container">
                        <img src="{{ asset('img/logo.png') }}" alt="Lark Fest Logo" class="auth-logo">
                    </div>
                    
                    <h1 class="auth-title">Join Epic Events</h1>
                    <p class="auth-subtitle">Create your account and start your epic journey</p>
                    
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Full Name
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}"
                                       placeholder="Enter your full name"
                                       required 
                                       autofocus>
                                @error('name')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="fab fa-whatsapp me-2"></i>WhatsApp Number
                                </label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone') }}"
                                       placeholder="08xxxxxxxxxx"
                                       required>
                                @error('phone')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}"
                                   placeholder="Enter your email address"
                                   required>
                            @error('email')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <div class="password-input-wrapper">
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Create a strong password"
                                       required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-eye"></i>
                                </button>
                            </div>
                            <div id="passwordStrength" class="password-strength"></div>
                            @error('password')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-2"></i>Confirm Password
                            </label>
                            <div class="password-input-wrapper">
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="form-control" 
                                       placeholder="Confirm your password"
                                       required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="terms" 
                                       name="terms" 
                                       required>
                                <label class="form-check-label" for="terms" style="color: var(--text-gray);">
                                    I agree to the <a href="#" style="color: var(--accent-blue);">Terms of Service</a> and <a href="#" style="color: var(--accent-blue);">Privacy Policy</a>
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-auth">
                            <i class="fas fa-rocket me-2"></i>Create Account
                        </button>
                    </form>
                    
                    <div class="auth-link">
                        <p style="color: var(--text-gray); margin-bottom: 0.5rem;">Already have an account?</p>
                        <a href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(inputId + '-eye');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('passwordStrength');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);
        
        strengthIndicator.className = 'password-strength strength-' + strength.level;
        strengthIndicator.innerHTML = '<i class="fas fa-shield-alt me-1"></i>' + strength.text;
    });
    
    function checkPasswordStrength(password) {
        let score = 0;
        
        if (password.length >= 8) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        
        if (score < 3) {
            return { level: 'weak', text: 'Weak password' };
        } else if (score < 5) {
            return { level: 'medium', text: 'Medium strength' };
        } else {
            return { level: 'strong', text: 'Strong password' };
        }
    }
});
</script>
@endsection