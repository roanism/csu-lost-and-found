<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - CSU Lost and Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background: url('{{ asset('images/csu-campus.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(248, 249, 250, 0.75);
            z-index: -1;
        }
        .admin-sidebar {
            background: #006400;
            min-height: 100vh;
            color: white;
            padding: 20px;
            position: fixed;
            width: inherit;
            max-width: inherit;
        }
        .admin-sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .admin-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .admin-sidebar .nav-link.active {
            background: #FFD700;
            color: #006400;
        }
        .admin-content {
            padding: 20px;
            margin-left: 16.666667%;
        }
        .admin-header {
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-radius: 10px;
            backdrop-filter: blur(5px);
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            backdrop-filter: blur(5px);
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card i {
            font-size: 2rem;
            color: #006400;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
        }
        .table {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .table thead {
            background: #006400;
            color: white;
        }
        .btn-csu {
            background: #FFD700;
            color: #006400;
            border: none;
            font-weight: 600;
        }
        .btn-csu:hover {
            background: #e6c200;
            color: #004d00;
        }
        .section {
            scroll-margin-top: 20px;
        }
        .alert {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="admin-sidebar">
                <h4 class="mb-4">Admin Panel</h4>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') && !request()->has('section') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->has('section') && request()->section === 'posts' ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard', ['section' => 'posts']) }}">
                        <i class="fas fa-list mr-2"></i> All Posts
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}" 
                       href="{{ route('admin.reports') }}">
                        <i class="fas fa-flag mr-2"></i> Reports
                        @if(isset($pendingReports) && $pendingReports > 0)
                            <span class="badge bg-danger ms-1">{{ $pendingReports }}</span>
                        @endif
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.messages') ? 'active' : '' }}" 
                       href="{{ route('admin.messages') }}">
                        <i class="fas fa-envelope mr-2"></i> Messages
                        @if(isset($unreadMessages) && $unreadMessages > 0)
                            <span class="badge bg-danger ms-1">{{ $unreadMessages }}</span>
                        @endif
                    </a>
                    <a class="nav-link" href="{{ route('admin.logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </nav>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="admin-content">
                <div class="admin-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Admin Dashboard</h4>
                    <span class="text-muted">Welcome, Admin</span>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add smooth scrolling to all links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Update active state based on scroll position
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('.section');
        const navLinks = document.querySelectorAll('.nav-link');
        
        sections.forEach(section => {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 150 && rect.bottom >= 150) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href').includes(section.id)) {
                        link.classList.add('active');
                    }
                });
            }
        });
    });
</script>
</body>
</html> 