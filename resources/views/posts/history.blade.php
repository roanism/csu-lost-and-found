@extends('layouts.app')

@section('content')
<div class="hero-section text-center py-4 mb-4" style="background: linear-gradient(rgba(0, 100, 0, 0.9), rgba(0, 100, 0, 0.9)); color: #FFD700; border-radius: 10px;">
    <div class="container">
        <h1 class="display-4 mb-2" style="color: #FFD700; font-size: 2.5rem;">History</h1>
        <p class="lead mb-3" style="color: #fff;">View all claimed and returned items</p>
    </div>
</div>

<div class="card mb-3 filter-card">
    <div class="card-header bg-white py-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Filter History</h5>
                <p class="mb-0" style="font-size: 0.875rem; color: #666;">Claimed/Returned Items</p>
            </div>
            <div class="d-flex gap-2">
                <div class="btn-group">
                    <a href="{{ route('posts.history', ['sort' => 'latest']) }}" 
                       class="btn btn-sm btn-outline-secondary {{ request('sort', 'latest') === 'latest' ? 'active' : '' }}">
                        <i class="fas fa-clock"></i> Latest
                    </a>
                    <a href="{{ route('posts.history', ['sort' => 'oldest']) }}" 
                       class="btn btn-sm btn-outline-secondary {{ request('sort') === 'oldest' ? 'active' : '' }}">
                        <i class="fas fa-history"></i> Oldest
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body py-2">
        <form action="{{ route('posts.history') }}" method="GET">
            <div class="row g-2">
                <!-- Search Box -->
                <div class="col-12">
                    <div class="search-box">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Search by reference number" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                Search
                            </button>
                            @if(request('search'))
                                <a href="{{ route('posts.history', array_merge(request()->except('search'), ['page' => 1])) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="form-label mb-1">
                            <i class="fas fa-tag me-1"></i> Type
                        </label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>Lost Items</option>
                            <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>Found Items</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="form-label mb-1">
                            <i class="fas fa-folder me-1"></i> Category
                        </label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="clothing" {{ request('category') == 'clothing' ? 'selected' : '' }}>Clothing</option>
                            <option value="documents" {{ request('category') == 'documents' ? 'selected' : '' }}>Documents</option>
                            <option value="accessories" {{ request('category') == 'accessories' ? 'selected' : '' }}>Accessories</option>
                            <option value="others" {{ request('category') == 'others' ? 'selected' : '' }}>Others</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="form-label mb-1">
                            <i class="fas fa-calendar me-1"></i> Date Filter
                        </label>
                        <div class="d-flex gap-2">
                            <select name="year" class="form-select form-select-sm">
                                <option value="">All Years</option>
                                @for($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                            <select name="month" class="form-select form-select-sm">
                                <option value="">All Months</option>
                                @foreach(['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'] as $value => $label)
                                    <option value="{{ $value }}" {{ request('month') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <input type="date" name="date_filter" class="form-control form-control-sm" value="{{ request('date_filter') }}" placeholder="Specific Date (Optional)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end mt-2">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-filter me-1"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>
</div>

@if($posts->count())
    <div class="row">
        @foreach($posts as $post)
            <div class="col-md-4 col-lg-3 mb-3">
                <div class="card h-100">
                    @if($post->image_path)
                        <div class="card-img-container" style="position: relative; padding-top: 75%; overflow: hidden; background: #f8f9fa;">
                            <img src="{{ asset('storage/' . $post->image_path) }}" 
                                 class="card-img-top" 
                                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;">
                        </div>
                    @endif
                    <div class="card-header py-2">
                        <small class="d-block text-truncate">{{ ucfirst($post->type) }}: {{ $post->item_name }}</small>
                    </div>
                    <div class="card-body py-2">
                        <p class="card-text small mb-2">{{ Str::limit($post->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge badge-{{ $post->status == 'claimed' ? 'warning' : 'success' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-csu btn-sm">View</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $posts->links() }}
@else
    <div class="alert alert-info">
        No historical items found.
    </div>
@endif

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.input-group {
    width: 100%;
}

.input-group .form-control {
    border-right: none;
}

.input-group .btn {
    border-left: none;
}

.btn-group {
    gap: 0.25rem;
}

.filter-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1rem;
}

.filter-card .card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    padding: 0.5rem 1rem;
}

.filter-card .card-body {
    padding: 0.75rem;
}

.search-box {
    margin-bottom: 0.25rem;
}

.search-box .input-group {
    border-radius: 0.25rem;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.search-box .input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-right: none;
    padding: 0.5rem 0.75rem;
    width: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.search-box .form-control {
    border: 1px solid #dee2e6;
    border-left: none;
    border-right: none;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    height: 38px;
}

.search-box .btn-primary {
    border-radius: 0;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    min-width: 80px;
    height: 38px;
}

.search-box .btn-outline-secondary {
    border-radius: 0;
    padding: 0.5rem;
    font-size: 0.875rem;
    border-left: none;
    height: 38px;
    width: 38px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.filter-group {
    background-color: #f8f9fa;
    padding: 0.5rem;
    border-radius: 0.25rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    height: 100%;
}

.filter-group .form-label {
    font-size: 0.75rem;
    margin-bottom: 0.25rem;
    color: #6c757d;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.filter-group .form-select,
.filter-group .form-control {
    border: 1px solid #dee2e6;
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
    height: 32px;
    background-color: #fff;
    width: 100%;
}

.filter-group .form-select:focus,
.filter-group .form-control:focus {
    box-shadow: none;
    border-color: #dee2e6;
}

.btn-group .btn {
    height: 32px;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

@media (max-width: 768px) {
    .filter-card .card-header {
        padding: 0.5rem;
    }
    
    .filter-card .card-body {
        padding: 0.5rem;
    }
    
    .filter-card .card-header .d-flex {
        flex-direction: column;
        width: 100%;
        gap: 0.5rem;
    }
    
    .filter-card .btn-group {
        width: 100%;
        justify-content: center;
    }
    
    .filter-group {
        margin-bottom: 0.5rem;
    }
    
    .search-box {
        margin-bottom: 0.5rem;
    }
    
    .search-box .btn-primary {
        min-width: auto;
        padding: 0.5rem 0.75rem;
    }
    
    .btn-group .btn {
        flex: 1;
    }
}
</style>
@endsection 