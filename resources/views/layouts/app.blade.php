<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSU Lost and Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar { background: #006400; }
        .navbar-brand, .nav-link, .navbar-text { color: #fff !important; }
        .btn-csu { background: #FFD700; color: #006400; border: none; }
        .btn-csu:hover { background: #e6c200; color: #004d00; }
        .csu-header { color: #006400; }
        .card-header { background: #006400; color: #fff; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="{{ route('home') }}">CSU Lost & Found</a>
    <div class="ml-auto">
        <a href="{{ route('posts.create') }}" class="btn btn-csu">Post Lost/Found Item</a>
    </div>
</nav>
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @yield('content')
</div>
</body>
</html>