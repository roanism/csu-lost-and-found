@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Message Details</h5>
                    <div>
                        <a href="{{ route('admin.messages') }}" class="btn btn-secondary btn-sm me-2">
                            <i class="fas fa-arrow-left"></i> Back to Messages
                        </a>
                        @if(!$message->is_read)
                            <form action="{{ route('admin.messages.mark-read', $message->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-check"></i> Mark as Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Sender Information</h6>
                                <div class="p-3 bg-light rounded">
                                    <p class="mb-2"><strong>Name:</strong> {{ $message->name }}</p>
                                    <p class="mb-2">
                                        <strong>Email:</strong>
                                        @if($message->email)
                                            <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                    <p class="mb-2">
                                        <strong>Phone:</strong>
                                        @if($message->phone)
                                            <a href="tel:{{ $message->phone }}">{{ $message->phone }}</a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                    <p class="mb-0"><strong>User Type:</strong> {{ ucfirst($message->user_type) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Message Information</h6>
                                <div class="p-3 bg-light rounded">
                                    <p class="mb-2"><strong>Status:</strong>
                                        @if(!$message->is_read)
                                            <span class="badge bg-danger">Unread</span>
                                        @else
                                            <span class="badge bg-success">Read</span>
                                        @endif
                                    </p>
                                    <p class="mb-2"><strong>Date Received:</strong> {{ $message->created_at->format('M d, Y h:i A') }}</p>
                                    <p class="mb-0"><strong>Message ID:</strong> {{ $message->id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Message Content</h6>
                                <div class="p-4 bg-light rounded">
                                    {{ $message->message }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: none;
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.bg-light {
    background-color: #f8f9fa !important;
}

.badge {
    padding: 0.5em 0.75em;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.text-muted {
    color: #6c757d !important;
}

h6.text-muted {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.bg-light.rounded {
    border: 1px solid rgba(0, 0, 0, 0.125);
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .p-4 {
        padding: 1rem !important;
    }
}
</style>
@endsection 