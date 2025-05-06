@extends('layouts.app')

@section('content')
<a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">Back</a>
<div class="card mb-4">
    @if($post->image_path)
        <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top" style="max-height:300px;object-fit:cover;">
    @endif
    <div class="card-header">
        {{ ucfirst($post->type) }}: {{ $post->item_name }}
    </div>
    <div class="card-body">
        <p><strong>Description:</strong> {{ $post->description }}</p>
        <p><strong>Category:</strong> {{ $post->category }}</p>
        <p><strong>Location:</strong> {{ $post->location }}</p>
        <p><strong>Contact Name:</strong> {{ $post->contact_name }}</p>
        <p><strong>Contact Info:</strong> {{ $post->contact_info }}</p>
        <p><strong>Status:</strong> {{ ucfirst($post->status) }}</p>
        <p><strong>Reference Number:</strong> {{ $post->reference_number }}</p>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
</div>

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

@if($post->status == 'open')
    <div class="card mb-4">
        <div class="card-header">Mark as Claimed or Returned</div>
        <div class="card-body">
            <form method="POST" action="{{ route('claims.store', $post->id) }}">
                @csrf
                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" name="claimer_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Your Contact</label>
                    <input type="text" name="claimer_contact" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Action</label>
                    <select name="claim_type" class="form-control" required>
                        <option value="claimed">Claimed</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Notes (optional)</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-csu">Submit</button>
            </form>
        </div>
    </div>
@endif
@endsection