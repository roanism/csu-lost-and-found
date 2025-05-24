@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Search Box -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reports') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search reports" value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.reports', array_merge(request()->except('search'), ['page' => 1])) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="btn-group float-end">
                        <a href="{{ route('admin.reports', array_merge(request()->except(['status', 'sort']), ['status' => 'all'])) }}" 
                           class="btn btn-outline-primary {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">
                            All
                        </a>
                        <a href="{{ route('admin.reports', array_merge(request()->except(['status', 'sort']), ['status' => 'pending'])) }}" 
                           class="btn btn-outline-primary {{ request('status') == 'pending' ? 'active' : '' }}">
                            Pending
                        </a>
                        <a href="{{ route('admin.reports', array_merge(request()->except(['status', 'sort']), ['status' => 'resolved'])) }}" 
                           class="btn btn-outline-primary {{ request('status') == 'resolved' ? 'active' : '' }}">
                            Resolved
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Reports</h5>
                <div class="d-flex gap-3 align-items-center">
                    <form id="bulk-delete-form" action="{{ route('admin.reports.bulk-delete') }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="report_ids" id="selected-reports">
                    </form>
                    <button type="button" id="bulk-delete-btn" class="btn btn-sm btn-danger d-none" onclick="confirmBulkDelete()">
                        <i class="fas fa-trash me-1"></i>Delete Selected
                    </button>
                    <form action="{{ route('admin.reports') }}" method="GET" class="d-flex align-items-center">
                        <div class="filter-group" style="width: 300px;">
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
                        @if(request('date_filter') || request('year') || request('month'))
                            <a href="{{ route('admin.reports', array_merge(request()->except(['date_filter', 'year', 'month']), ['page' => 1])) }}" 
                               class="btn btn-sm btn-outline-secondary ms-2">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </form>
                    <div class="btn-group">
                        <a href="{{ route('admin.reports', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}" 
                           class="btn btn-sm btn-outline-secondary {{ request('sort', 'latest') === 'latest' ? 'active' : '' }}">
                            <i class="fas fa-clock"></i> Latest
                        </a>
                        <a href="{{ route('admin.reports', array_merge(request()->except('sort'), ['sort' => 'oldest'])) }}" 
                           class="btn btn-sm btn-outline-secondary {{ request('sort') === 'oldest' ? 'active' : '' }}">
                            <i class="fas fa-history"></i> Oldest
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="bg-csu text-white">
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="select-all">
                                    <label class="form-check-label" for="select-all" style="color: #fff; font-size: 0.875rem;">
                                        Select All
                                    </label>
                                </div>
                            </th>
                            <th>Reported Post</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input report-checkbox" type="checkbox" value="{{ $report->id }}">
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.posts.show', $report->post_id) }}" class="text-decoration-none">
                                        {{ $report->post->item_name }}
                                    </a>
                                </td>
                                <td>{{ $report->reason }}</td>
                                <td>
                                    @if($report->resolved)
                                        <span class="badge bg-success">Resolved</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-muted small">
                                            <i class="fas fa-calendar me-1"></i>{{ $report->created_at->format('M d, Y') }}
                                        </span>
                                        <span class="text-muted small">
                                            <i class="fas fa-clock me-1"></i>{{ $report->created_at->format('h:i A') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if(!$report->resolved)
                                            <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to mark this report as resolved?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this report?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No reports found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $reports->links() }}
        </div>
    </div>
</div>

<style>
.container-fluid {
    max-width: 2000px;
    margin: 0 auto;
    padding: 0 2rem;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1rem;
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    padding: 1rem 2rem;
}

.card-body {
    padding: 1rem 2rem;
}

.table th {
    font-weight: 600;
    border-top: none;
    white-space: nowrap;
    background-color: #006400 !important;
    color: #fff;
    padding: 1rem 1.5rem;
}

.table td {
    vertical-align: middle;
    padding: 1rem 1.5rem;
}

.table thead tr {
    background-color: #006400 !important;
}

.table-responsive {
    margin: 0;
    padding: 0;
}

/* Timestamp styles */
.table td .d-flex.flex-column {
    line-height: 1.2;
}

.table td .d-flex.flex-column small {
    font-size: 0.75rem;
    opacity: 0.8;
}

.btn-group {
    gap: 0.25rem;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.badge {
    padding: 0.5em 0.75em;
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

.filter-group {
    background-color: #f8f9fa;
    padding: 0.5rem;
    border-radius: 0.25rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.filter-group .form-control {
    border: 1px solid #dee2e6;
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
    height: 32px;
    background-color: #fff;
    width: 100%;
}

.filter-group .form-control:focus {
    box-shadow: none;
    border-color: #dee2e6;
}

/* Bulk selection styles */
.form-check {
    margin: 0;
    padding: 0;
}

.form-check-input {
    cursor: pointer;
}

#bulk-delete-btn {
    transition: all 0.3s ease;
}

#bulk-delete-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 0 1rem;
    }
    
    .card-header {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .card-header .d-flex {
        flex-direction: column;
        width: 100%;
    }
    
    .card-header .d-flex > div {
        width: 100%;
    }
    
    .filter-group {
        width: 100% !important;
    }
    
    .btn-group {
        width: 100%;
        justify-content: center;
    }
    
    .table-responsive {
        margin: 0;
    }
    
    .table td, .table th {
        padding: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.report-checkbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const selectedReportsInput = document.getElementById('selected-reports');

    // Select all functionality
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkDeleteButton();
    });

    // Individual checkbox functionality
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkDeleteButton();
            // Update select all checkbox
            selectAll.checked = Array.from(checkboxes).every(cb => cb.checked);
        });
    });

    // Update bulk delete button visibility
    function updateBulkDeleteButton() {
        const selectedCount = document.querySelectorAll('.report-checkbox:checked').length;
        bulkDeleteBtn.classList.toggle('d-none', selectedCount === 0);
    }
});

// Confirm and submit bulk delete
function confirmBulkDelete() {
    const selectedReports = Array.from(document.querySelectorAll('.report-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedReports.length === 0) return;

    if (confirm(`Are you sure you want to delete ${selectedReports.length} selected report(s)?`)) {
        document.getElementById('selected-reports').value = JSON.stringify(selectedReports);
        document.getElementById('bulk-delete-form').submit();
    }
}
</script>
@endsection 