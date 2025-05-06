@extends('layouts.app')

@section('content')
<h2 class="csu-header mb-4">Lost and Found Items</h2>
<div class="mb-3">
    <form method="GET" action="{{ route('posts.index') }}" class="form-inline">
        <select name="type" class="form-control mr-2">
            <option value="">All</option>
            <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>Lost</option>
            <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>Found</option>
        </select>
        <input type="text" name="category" class="form-control mr-2" placeholder="Category" value="{{ request('category') }}">
        <button type="submit" class="btn btn-csu">Filter</button>
    </form>
</div>
@if($posts->count())
    <div class="row">
        @foreach($posts as $post)
            <div class="col-md-6 mb-4">
                <div class="card">
                    @if($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top" style="max-height:200px;object-fit:cover;">
                    @endif
                    <div class="card-header">
                        {{ ucfirst($post->type) }}: {{ $post->item_name }}
                    </div>
                    <div class="card-body">
                        <p>{{ Str::limit($post->description, 100) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($post->status) }}</p>
                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-csu btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $posts->links() }}
@else
    <p>No posts found.</p>
@endif
@endsection