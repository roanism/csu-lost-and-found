@extends('layouts.app')

@section('content')
<h2 class="csu-header mb-4">Admin Dashboard</h2>
<a href="{{ route('admin.logout') }}" class="btn btn-danger mb-3"
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<h4>All Posts</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Type</th>
            <th>Item Name</th>
            <th>Status</th>
            <th>Reference #</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($posts as $post)
        <tr>
            <td>{{ ucfirst($post->type) }}</td>
            <td>{{ $post->item_name }}</td>
            <td>{{ ucfirst($post->status) }}</td>
            <td>{{ $post->reference_number }}</td>
            <td>
                <form method="POST" action="{{ route('admin.posts.delete', $post->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Delete this post?')">Delete</button>
                </form>
                <form method="POST" action="{{ route('admin.posts.status', $post->id) }}" style="display:inline;">
                    @csrf
                    <select name="status" onchange="this.form.submit()" class="form-control form-control-sm d-inline" style="width:auto;display:inline;">
                        <option value="open" {{ $post->status == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="claimed" {{ $post->status == 'claimed' ? 'selected' : '' }}>Claimed</option>
                        <option value="returned" {{ $post->status == 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $posts->links() }}

<h4 class="mt-5">Reports</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Post Ref #</th>
            <th>Reporter</th>
            <th>Reason</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
        <tr>
            <td>{{ $report->post->reference_number ?? 'N/A' }}</td>
            <td>{{ $report->reporter_name }}</td>
            <td>{{ $report->reason }}</td>
            <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $reports->links() }}
@endsection