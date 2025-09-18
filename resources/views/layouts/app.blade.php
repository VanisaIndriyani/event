<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', ' lark fest - Where extraordinary moments become unforgettable memories')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        :root {
            --primary-navy: #0B1426;
            --secondary-navy: #1A2332;
            --accent-blue: #2563EB;
            --bright-blue: #3B82F6;
            --cyan: #06B6D4;
            --purple: #8B5CF6;
            --text-light: #F8FAFC;
            --text-gray: #94A3B8;
            --border-color: #334155;
            --glass-bg: rgba(30, 41, 59, 0.7);
            --gradient-primary: linear-gradient(135deg, #0B1426 0%, #1A2332 50%, #2563EB 100%);
            --gradient-accent: linear-gradient(135deg, #3B82F6 0%, #06B6D4 50%, #8B5CF6 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
            color: var(--text-light);
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
        }
        
        .futuristic-bg {
            background: var(--gradient-primary);
            position: relative;
        }
        
        .futuristic-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .glass-effect {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }
        
        /* Custom button animations */
        .btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            padding: 1rem 0;
        }
        
        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.25rem 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28248, 250, 252, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .navbar-brand {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }
        
        .navbar-logo {
            height: 50px;
            width: auto;
            filter: brightness(1.2);
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover .navbar-logo {
            transform: scale(1.1);
            filter: brightness(1.4);
        }
        
        .nav-link {
            color: var(--text-gray);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 8px 16px;
            border-radius: 20px;
            margin: 0 4px;
        }
        
        .nav-link:hover {
            color: var(--bright-blue);
            text-decoration: none;
            background: rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            color: white;
            text-decoration: none;
            background: var(--gradient-accent);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        
        .nav-link:focus {
            color: var(--bright-blue);
            text-decoration: none;
            background: rgba(59, 130, 246, 0.15);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .nav-link::after {
            display: none;
        }
        
        .container {
            max-width: 1200px;
        }
        
        /* Bootstrap overrides */
        .btn-primary {
            background: var(--gradient-accent) !important;
            border: none !important;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3) !important;
        }
        
        .btn-secondary {
            background: transparent !important;
            border: 2px solid var(--accent-blue) !important;
            color: var(--accent-blue) !important;
            font-weight: 600;
        }
        
        .btn-secondary:hover {
            background: var(--accent-blue) !important;
            color: white !important;
            border-color: var(--accent-blue) !important;
        }
        
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 80px);
        }
        
        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: rgba(59, 130, 246, 0.3);
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 12px 16px;
            color: var(--text-light);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background: rgba(255, 255, 255, 0.1);
        }
        
        .form-control::placeholder {
            color: var(--text-gray);
        }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border-left-color: #22C55E;
            color: #86EFAC;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border-left-color: #EF4444;
            color: #FCA5A5;
        }
        
        .text-gradient {
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 0.5rem;
            }
            
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .btn-primary, .btn-secondary {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="futuristic-bg">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center">
                <img src="{{ asset('img/logo.png') }}" alt="Lark Fest Logo" class="navbar-logo me-2">
             
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('events') }}" class="nav-link {{ request()->routeIs('events*') ? 'active' : '' }}">
                            <i class="fas fa-calendar me-1"></i>Events
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('merchandise') }}" class="nav-link {{ request()->routeIs('merchandise*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-bag me-1"></i>Merchandise
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('portfolio') }}" class="nav-link {{ request()->routeIs('portfolio') ? 'active' : '' }}">
                            <i class="fas fa-images me-1"></i>Portfolio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('collab') }}" class="nav-link {{ request()->routeIs('collab') ? 'active' : '' }}">
                            <i class="fas fa-handshake me-1"></i>Collab
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-2">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                <i class="fas fa-cog me-1"></i>Admin
                            </a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="nav-link">
                                <i class="fas fa-user me-1"></i>Dashboard
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Session Persistence Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            // Session persistence - ping server setiap 15 menit untuk menjaga session aktif
            function keepSessionAlive() {
                const dashboardRoute = @if(auth()->user()->role === 'admin') '{{ route("admin.dashboard") }}' @else '{{ route("user.dashboard") }}' @endif;
                
                fetch(dashboardRoute, {
                    method: 'HEAD',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                }).catch(function(error) {
                    console.log('Session keep-alive failed:', error);
                });
            }
            
            // Jalankan keep-alive setiap 15 menit (900000 ms)
            setInterval(keepSessionAlive, 900000);
            
            // Juga jalankan saat user berinteraksi dengan halaman
            let lastActivity = Date.now();
            const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
            
            function updateActivity() {
                const now = Date.now();
                if (now - lastActivity > 300000) { // 5 menit
                    lastActivity = now;
                    keepSessionAlive();
                }
            }
            
            activityEvents.forEach(function(event) {
                document.addEventListener(event, updateActivity, true);
            });
            
            // Prevent automatic logout on page refresh
            window.addEventListener('beforeunload', function() {
                // Set flag bahwa ini adalah refresh, bukan logout
                sessionStorage.setItem('page_refreshing', 'true');
            });
            
            // Clear refresh flag setelah page load
            window.addEventListener('load', function() {
                sessionStorage.removeItem('page_refreshing');
            });
            @endauth
        });
    </script>
    
    @stack('scripts')
</body>
</html>