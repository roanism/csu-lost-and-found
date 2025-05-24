@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Post Details</h5>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Basic Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 150px;">Reference Number:</th>
                                    <td>{{ $post->reference_number }}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>{{ ucfirst($post->type) }}</td>
                                </tr>
                                <tr>
                                    <th>Item Name:</th>
                                    <td>{{ $post->item_name }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $post->category }}</td>
                                </tr>
                                <tr>
                                    <th>Location:</th>
                                    <td>{{ $post->location }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $post->status == 'open' ? 'primary' : ($post->status == 'claimed' ? 'warning' : 'success') }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date Posted:</th>
                                    <td>{{ $post->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Contact Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 150px;">Contact Name:</th>
                                    <td>{{ $post->contact_name }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Info:</th>
                                    <td>{{ $post->contact_info }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Description</h6>
                        <p class="mb-0">{{ $post->description }}</p>
                    </div>

                    @if($post->image_path)
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Image</h6>
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->item_name }}" class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                    @endif

                    @if(in_array($post->status, ['claimed', 'returned']) && $post->claims->isNotEmpty())
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">{{ $post->status === 'claimed' ? 'Claimant' : 'Returner' }} Information</h6>
                        <div class="p-3 bg-light rounded">
                            @php
                                $claim = $post->claims->first();
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Name:</strong> {{ $claim->claimer_name }}</p>
                                    <p class="mb-2">
                                        <strong>Email:</strong>
                                        @if($claim->claimer_email)
                                            <a href="mailto:{{ $claim->claimer_email }}">{{ $claim->claimer_email }}</a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Phone:</strong>
                                        @if($claim->claimer_phone)
                                            <a href="tel:{{ $claim->claimer_phone }}">{{ $claim->claimer_phone }}</a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                    <p class="mb-2"><strong>Remarks:</strong> {{ $claim->claim_reason }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($post->reports->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Reports</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Reporter Name</th>
                                    <th>Reason</th>
                                    <th>Date Reported</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($post->reports as $report)
                                <tr>
                                    <td>{{ $report->reporter_name }}</td>
                                    <td>{{ $report->reason }}</td>
                                    <td>{{ $report->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="card mb-4">
                        <div class="card-header">Post Status</div>
                        <div class="card-body">
                            @if($post->status === 'open')
                                <span class="badge bg-success">Open</span>
                            @elseif($post->status === 'pending_claim' || $post->status === 'pending_return')
                                <form action="{{ route('admin.approve-claim', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Approve {{ $post->status === 'pending_return' ? 'Return' : 'Claim' }}
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-info">{{ ucfirst($post->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this post? This action cannot be undone.')">
                            Delete Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 