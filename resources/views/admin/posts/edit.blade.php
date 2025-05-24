@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Post</h5>
                <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Post
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="lost" {{ $post->type == 'lost' ? 'selected' : '' }}>Lost</option>
                            <option value="found" {{ $post->type == 'found' ? 'selected' : '' }}>Found</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="open" {{ $post->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="pending_claim" {{ $post->status == 'pending_claim' ? 'selected' : '' }}>Pending Claim</option>
                            <option value="pending_return" {{ $post->status == 'pending_return' ? 'selected' : '' }}>Pending Return</option>
                            <option value="claimed" {{ $post->status == 'claimed' ? 'selected' : '' }}>Claimed</option>
                            <option value="returned" {{ $post->status == 'returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="item_name" class="form-label">Item Name</label>
                    <input type="text" class="form-control @error('item_name') is-invalid @enderror" 
                           id="item_name" name="item_name" value="{{ old('item_name', $post->item_name) }}" required>
                    @error('item_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3" required>{{ old('description', $post->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror" 
                               id="category" name="category" value="{{ old('category', $post->category) }}">
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location', $post->location) }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="contact_name" class="form-label">Contact Name</label>
                        <input type="text" class="form-control @error('contact_name') is-invalid @enderror" 
                               id="contact_name" name="contact_name" value="{{ old('contact_name', $post->contact_name) }}" required>
                        @error('contact_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="contact_info" class="form-label">Contact Information</label>
                        <input type="text" class="form-control @error('contact_info') is-invalid @enderror" 
                               id="contact_info" name="contact_info" value="{{ old('contact_info', $post->contact_info) }}" required>
                        @error('contact_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    @if($post->image_path)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="Current image" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.container-fluid {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.form-label {
    font-weight: 500;
}

.btn {
    padding: 0.5rem 1rem;
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 0 0.5rem;
    }
    
    .card-header {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endsection 