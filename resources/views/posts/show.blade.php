@extends('layouts.app')

@section('content')
<a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">Back</a>
<div class="card mb-4">
    @if($post->image_path)
        <div class="card-img-container" style="position: relative; padding-top: 56.25%; overflow: hidden; background: #f8f9fa;">
            <img src="{{ asset('storage/' . $post->image_path) }}" 
                 class="card-img-top" 
                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;">
        </div>
    @endif
    <div class="card-header">
        {{ ucfirst($post->type) }}: {{ $post->item_name }}
    </div>
    <div class="card-body">
        <p><strong>Description:</strong> {{ $post->description }}</p>
        <p><strong>Category:</strong> {{ $post->category }}</p>
        <p><strong>Location:</strong> {{ $post->location }}</p>
        @if($post->status === 'open')
            <p><strong>Contact Name:</strong> {{ $post->contact_name }}</p>
            <p><strong>Contact Info:</strong> {{ $post->contact_info }}</p>
        @endif
        <p><strong>Status:</strong> {{ ucfirst($post->status) }}</p>
        <p><strong>Reference Number:</strong> {{ $post->reference_number }}</p>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
</div>

@if($post->status === 'open')
    <div class="card mb-4">
        <div class="card-header">Report Inappropriate Post</div>
        <div class="card-body">
            <form method="POST" action="{{ route('reports.store', $post->id) }}">
                @csrf
                <div class="form-group">
                    <label>Your Name (optional)</label>
                    <input type="text" name="reporter_name" class="form-control">
                </div>
                <div class="form-group">
                    <label>Your Contact (optional)</label>
                    <input type="text" name="reporter_contact" class="form-control">
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <textarea name="reason" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger">Report</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            @if($post->type === 'lost')
                Return Item
            @else
                Claim Item
            @endif
        </div>
        <div class="card-body">
            <form action="{{ route('claims.store', $post->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="claimer_name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="claimer_name" name="claimer_name" required>
                </div>
                <div class="mb-3">
                    <label for="claimer_email" class="form-label">Your Email</label>
                    <input type="email" class="form-control" id="claimer_email" name="claimer_email" required>
                </div>
                <div class="mb-3">
                    <label for="claimer_phone" class="form-label">Your Phone</label>
                    <input type="tel" class="form-control" id="claimer_phone" name="claimer_phone" required>
                </div>
                <div class="mb-3">
                    <label for="claim_reason" class="form-label">Additional Remarks</label>
                    <textarea class="form-control" id="claim_reason" name="claim_reason" rows="3" required></textarea>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        @if($post->type === 'lost')
                            Return Item
                        @else
                            Claim Item
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection