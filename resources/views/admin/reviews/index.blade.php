<x-adminLayout>
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-3xl font-bold text-gray-800">Manage Reviews</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('AdminDashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Reviews</li>
        </ol>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-filter me-1"></i>
                Filter Reviews
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="user_search" class="form-label">Search User (Name, Username, Email)</label>
                        <input type="text" name="user_search" id="user_search" class="form-control" value="{{ request('user_search') }}" placeholder="Enter user details...">
                    </div>
                    <div class="col-md-3">
                        <label for="house_search" class="form-label">Search House Title</label>
                        <input type="text" name="house_search" id="house_search" class="form-control" value="{{ request('house_search') }}" placeholder="Enter house title...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-star me-1"></i>
                All Reviews
            </div>
            <div class="card-body">
                @if($reviews->isNotEmpty())
                    <div class="table-responsive custom-table-container">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>House</th>
                                    <th>User</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $review)
                                    <tr>
                                        <td>{{ $review->id }}</td>
                                        <td>
                                            @if($review->house)
                                                <a href="{{ route('house.details', $review->house) }}" target="_blank">{{ Str::limit($review->house->title, 30) }}</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($review->user)
                                                {{ $review->user->full_name ?? $review->user->user_name }}
                                                <br><small class="text-muted">{{ $review->user->email }}</small>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                            @endfor
                                            ({{ $review->rating }}/5)
                                        </td>
                                        <td>{{ Str::limit($review->comment, 50) }}</td>
                                        <td>
                                            @if($review->is_approved)
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $review->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="action-buttons">
                                            @if(!$review->is_approved)
                                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve Review">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete Review">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <div class="alert alert-info" role="alert">
                        No reviews found matching your criteria.
                    </div>
                @endif
            </div>
        </div>
    </div>
    <style>
    .custom-table-container {
        border-radius: .25rem;
        overflow: hidden;
    }
    .table thead th {
        background-color: #343a40; /* Darker header for admin tables */
        color: white;
    }
    .action-buttons form {
        margin-bottom: 0; /* Ensure buttons are aligned */
    }
    .action-buttons .btn {
        padding: .25rem .5rem;
        font-size: .875rem;
    }
    </style>
</x-adminLayout>
