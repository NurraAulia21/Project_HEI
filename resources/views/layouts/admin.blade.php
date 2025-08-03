<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin - HEI Assessment')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #e6e7ee;
            min-height: 100vh;
            overflow-x: hidden;
        }

        :root {
            --bg-primary: #e6e7ee;
            --bg-card: #e6e7ee;
            
            --purple-soft: #b19cd9;
            --green-soft: #a8d5ba;
            --blue-soft: #a8c8ec;
            --yellow-soft: #f7e98e;
            --red-soft: #e5a3a3;
            --orange-soft: #ffa981;
            
            --shadow-dark: #d1d2d9;
            --shadow-light: #fbfcff;
            
            --text-primary: #3e4152;
            --text-secondary: #6c7293;
            --text-muted: #a7a9b8;
        }

        /* Typography */
        .text-h1 { font-size: 2.5rem; font-weight: 700; line-height: 1.2; color: var(--text-primary); }
        .text-h2 { font-size: 2rem; font-weight: 600; line-height: 1.3; color: var(--text-primary); }
        .text-h3 { font-size: 1.5rem; font-weight: 500; line-height: 1.4; color: var(--text-primary); }
        .text-h4 { font-size: 1.25rem; font-weight: 500; line-height: 1.5; color: var(--text-primary); }
        .text-body { font-size: 1rem; font-weight: 400; line-height: 1.6; color: var(--text-secondary); }

        /* Layout */
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--bg-card);
            padding: 2rem 1.5rem;
            box-shadow: 
                inset -4px 0 8px var(--shadow-dark),
                inset 4px 0 8px var(--shadow-light);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar.mobile-hidden {
            transform: translateX(-100%);
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(209, 210, 217, 0.3);
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .sidebar-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .sidebar-nav {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            text-decoration: none;
            color: var(--text-secondary);
            border-radius: 16px;
            transition: all 0.3s ease;
            font-weight: 500;
            background: var(--bg-card);
            box-shadow: 
                4px 4px 8px var(--shadow-dark),
                -4px -4px 8px var(--shadow-light);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .nav-link:hover {
            transform: translateY(-1px);
            box-shadow: 
                6px 6px 12px var(--shadow-dark),
                -6px -6px 12px var(--shadow-light);
            color: var(--text-primary);
        }

        .nav-link.active {
            background: var(--blue-soft);
            color: white;
            box-shadow: 
                inset 2px 2px 4px rgba(0, 0, 0, 0.1),
                4px 4px 8px var(--shadow-dark);
        }

        .nav-icon {
            margin-right: 0.75rem;
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--bg-card);
            border: none;
            padding: 0.75rem;
            border-radius: 12px;
            box-shadow: 
                4px 4px 8px var(--shadow-dark),
                -4px -4px 8px var(--shadow-light);
            cursor: pointer;
            color: var(--text-primary);
            font-size: 1.25rem;
        }

        /* Content Sections */
        .section {
            background: var(--bg-card);
            padding: 2.5rem;
            margin-bottom: 2rem;
            border-radius: 25px;
            box-shadow: 
                12px 12px 24px var(--shadow-dark),
                -12px -12px 24px var(--shadow-light);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .card {
            background: var(--bg-card);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 
                10px 10px 20px var(--shadow-dark),
                -10px -10px 20px var(--shadow-light);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin: 1.5rem 0;
        }

        /* Buttons */
        .btn {
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 
                6px 6px 12px var(--shadow-dark),
                -6px -6px 12px var(--shadow-light);
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-family: inherit;
            font-size: 1rem;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 
                8px 8px 16px var(--shadow-dark),
                -8px -8px 16px var(--shadow-light);
        }

        .btn-primary { background: var(--blue-soft); color: white; }
        .btn-success { background: var(--green-soft); color: white; }
        .btn-warning { background: var(--yellow-soft); color: var(--text-primary); }
        .btn-danger { background: var(--red-soft); color: white; }
        .btn-secondary { background: var(--purple-soft); color: white; }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.875rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            margin: 1rem 0;
            border-radius: 12px;
            font-weight: 500;
        }

        .alert-success { background: var(--green-soft); color: white; }
        .alert-danger { background: var(--red-soft); color: white; }
        .alert-info { background: var(--blue-soft); color: white; }
        .alert-warning { background: var(--yellow-soft); color: var(--text-primary); }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: var(--bg-card);
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 
                8px 8px 16px var(--shadow-dark),
                -8px -8px 16px var(--shadow-light);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        /* Utility Classes */
        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .align-items-center { align-items: center; }
        .mb-4 { margin-bottom: 2rem; }
        .me-2 { margin-right: 0.5rem; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-visible {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 4rem 1rem 1rem;
            }

            .mobile-toggle {
                display: block;
            }

            .text-h1 { font-size: 2rem; }
            .text-h2 { font-size: 1.75rem; }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
            }
        }

        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        @media (max-width: 768px) {
            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Mobile Toggle Button -->
        <button class="mobile-toggle" id="mobile-toggle">
            ☰
        </button>

        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">HEI Assessment</div>
                <div class="sidebar-subtitle">Admin Dashboard</div>
            </div>

            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">🏠</span>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.admins.index') }}" class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                        <span class="nav-icon">👥</span>
                        Kelola Admin
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.questions.index') }}" class="nav-link {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                        <span class="nav-icon">❓</span>
                        Kelola Pertanyaan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.answers.index') }}" class="nav-link {{ request()->routeIs('admin.answers.*') ? 'active' : '' }}">
                        <span class="nav-icon">📋</span>
                        Kelola Jawaban
                    </a>
                </li>
                <li class="nav-item" style="margin-top: 2rem;">
                    <a href="{{ route('admin.logout') }}" class="nav-link" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="nav-icon">🚪</span>
                        Logout
                    </a>
                </li>
            </ul>

            <!-- Logout Form -->
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>

        <!-- Main Content -->
        <main class="main-content" id="main-content">
            <!-- Page Header -->
            <div class="section">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="text-h1">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-body">@yield('page-description', 'Kelola sistem HEI Assessment')</p>
                    </div>
                    <div>
                        @yield('page-actions')
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
            @endif

            <!-- Main Content Area -->
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.getElementById('mobile-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const mainContent = document.getElementById('main-content');

            // Mobile toggle functionality
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('mobile-visible');
                sidebarOverlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('mobile-visible') ? 'hidden' : 'auto';
            });

            // Close sidebar when clicking overlay
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-visible');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            });

            // Close sidebar when clicking nav link on mobile
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('mobile-visible');
                        sidebarOverlay.classList.remove('active');
                        document.body.style.overflow = 'auto';
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('mobile-visible');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>