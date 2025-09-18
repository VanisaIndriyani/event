<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard') - Event Manager</title>
    
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
            --glass-bg: rgba(15, 23, 42, 0.8);
            --gradient-primary: linear-gradient(135deg, #0B1426 0%, #1A2332 50%, #2563EB 100%);
            --gradient-accent: linear-gradient(135deg, #3B82F6 0%, #06B6D4 50%, #8B5CF6 100%);
            --sidebar-width: 280px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--primary-navy);
            color: var(--text-light);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* Admin Layout */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--gradient-primary);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
            border-right: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }
        
        .sidebar-logo {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .sidebar-subtitle {
            font-size: 0.875rem;
            color: var(--text-gray);
            font-weight: 500;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-section {
            margin-bottom: 2rem;
        }
        
        .nav-section-title {
            padding: 0 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-gray);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .nav-item {
            margin-bottom: 0.25rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            border-left: 3px solid transparent;
            border-radius: 0 25px 25px 0;
            margin: 2px 0;
        }
        
        .nav-link:hover {
            background: rgba(59, 130, 246, 0.1);
            color: var(--bright-blue);
            border-left-color: var(--bright-blue);
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: var(--gradient-accent);
            color: white;
            border-left-color: var(--bright-blue);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        
        .nav-link:focus {
            background: rgba(59, 130, 246, 0.15);
            color: var(--bright-blue);
            border-left-color: var(--bright-blue);
            outline: none;
            box-shadow: inset 0 0 0 2px rgba(59, 130, 246, 0.3);
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1rem;
        }
        
        .nav-badge {
            margin-left: auto;
            background: var(--accent-blue);
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-weight: 600;
        }
        
        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: var(--primary-navy);
        }
        
        .admin-header {
            background: var(--secondary-navy);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-light);
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .header-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--gradient-accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-light);
        }
        
        .user-role {
            font-size: 0.75rem;
            color: var(--text-gray);
        }
        
        .admin-content {
            padding: 2rem;
        }
        
        /* Logout Button */
        .logout-btn {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #EF4444;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #EF4444;
            border-color: rgba(239, 68, 68, 0.5);
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .mobile-toggle {
                display: block;
                background: none;
                border: none;
                color: var(--text-light);
                font-size: 1.25rem;
                cursor: pointer;
            }
        }
        
        .mobile-toggle {
            display: none;
        }
        
        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        @media (max-width: 768px) {
            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo"> LARk FEST</div>
                <div class="sidebar-subtitle">Admin Dashboard</div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.analytics') }}" class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            Analytics
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Management</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i>
                            Events
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.form-fields.index') }}" class="nav-link {{ request()->routeIs('admin.form-fields.*') ? 'active' : '' }}">
                            <i class="fas fa-wpforms"></i>
                            Form Fields
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.merchandise.index') }}" class="nav-link {{ request()->routeIs('admin.merchandise.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-bag"></i>
                            Merchandise
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.registrations.index') }}" class="nav-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}">
                            <i class="fas fa-list-alt"></i>
                            Registrations
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.collaborations.index') }}" class="nav-link {{ request()->routeIs('admin.collaborations.*') ? 'active' : '' }}">
                            <i class="fas fa-handshake"></i>
                            Collaborations
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">System</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                    </div>

                    <div class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </aside>
        
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="d-flex align-items-center">
                    <button class="mobile-toggle" id="mobileToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="header-title">@yield('title', 'Dashboard')</h1>
                </div>
                
                <div class="header-actions">
                    <div class="header-user">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">Administrator</div>
                        </div>
                    </div>
                    
                   
                </div>
            </header>
            
            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mobile Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.getElementById('mobileToggle');
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            }
            
            function closeSidebar() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            }
            
            if (mobileToggle) {
                mobileToggle.addEventListener('click', toggleSidebar);
            }
            
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }
            
            // Close sidebar when clicking nav links on mobile
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });
            
            // Session persistence - ping server setiap 15 menit untuk menjaga session aktif
            function keepSessionAlive() {
                fetch('{{ route("admin.dashboard") }}', {
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
        });
    </script>
    
    @stack('scripts')
</body>
</html>