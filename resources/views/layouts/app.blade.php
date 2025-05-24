<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CSU Lost and Found</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <meta name="theme-color" content="#006400">
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background: url('/images/csu-campus.jpg') no-repeat center center fixed;
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
        .navbar { 
            background: linear-gradient(rgba(0, 100, 0, 0.9), rgba(0, 100, 0, 0.9)); 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand, .nav-link, .navbar-text { 
            color: #fff !important; 
        }
        .btn-csu { 
            background: #FFD700; 
            color: #006400; 
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-csu:hover { 
            background: #e6c200; 
            color: #004d00;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .csu-header { 
            color: #006400;
            font-weight: 600;
        }
        .card-header { 
            background: #006400; 
            color: #fff;
        }
        .hero-section {
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .hero-section img {
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }
        .card {
            transition: transform 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            background: rgba(255, 255, 255, 0.85);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .container {
            position: relative;
            z-index: 1;
        }
        .nav-buttons {
            display: flex;
            gap: 10px;
        }
        @media (max-width: 768px) {
            .nav-buttons {
                flex-direction: column;
                width: 100%;
                margin-top: 10px;
            }
            .nav-buttons .btn {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/csu-logo.png') }}" alt="CSU Logo" height="40" class="me-2">
            CSU Lost and Found
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.index') }}">Active Items</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.create') }}">Post Item</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.history') }}">History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact.create') }}">Contact Admin</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
</body>
</html>