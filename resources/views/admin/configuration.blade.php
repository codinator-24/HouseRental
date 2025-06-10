<x-adminLayout>
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-3xl font-bold text-gray-800">Admin Configuration & Tools</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('AdminDashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Configuration</li>
        </ol>

        @if(!app()->environment('local'))
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Production Environment Warning!</h4>
                <p>These tools are intended for local development and testing only. They should not be accessible or used in a production environment.</p>
            </div>
        @else
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Booking Time Shifter Section -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-hourglass-half me-1"></i>
                    <strong>Booking Time Shifter (Development Tool)</strong>
                </div>
                <div class="card-body">
                    <p class="alert alert-warning"><strong>DEVELOPMENT TOOL:</strong> This utility modifies booking creation dates for testing review eligibility. Use with caution. It is only available in the local environment.</p>
                    
                    <!-- Filters for Bookings -->
                    <form method="GET" action="{{ route('admin.configuration.index') }}" class="row g-3 mb-4 align-items-end">
                        <div class="col-md-3">
                            <label for="booking_status" class="form-label">Booking Status</label>
                            <select name="booking_status" id="booking_status" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('booking_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ request('booking_status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="completed" {{ request('booking_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="rejected" {{ request('booking_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="cancelled" {{ request('booking_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tenant_search" class="form-label">Tenant (Name/Email)</label>
                            <input type="text" name="tenant_search" id="tenant_search" class="form-control form-control-sm" value="{{ request('tenant_search') }}" placeholder="Search tenant...">
                        </div>
                        <div class="col-md-3">
                            <label for="house_title_search" class="form-label">House Title</label>
                            <input type="text" name="house_title_search" id="house_title_search" class="form-control form-control-sm" value="{{ request('house_title_search') }}" placeholder="Search house title...">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-sm btn-info w-100"><i class="fas fa-filter me-1"></i>Filter Bookings</button>
                        </div>
                         <div class="col-md-1">
                            <a href="{{ route('admin.configuration.index') }}" class="btn btn-sm btn-outline-secondary w-100" title="Clear Filters">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </form>

                    @if($bookings->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>House</th>
                                        <th>Tenant</th>
                                        <th>Created At</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Age Booking</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->id }}</td>
                                            <td>{{ Str::limit($booking->house?->title, 25) ?? 'N/A' }}</td>
                                            <td>{{ Str::limit($booking->tenant?->user_name, 20) ?? 'N/A' }}</td>
                                            <td>{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                                            <td>{{ $booking->month_duration }}m</td>
                                            <td><span class="badge bg-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'pending' || $booking->status == 'accepted' ? 'primary' : 'secondary') }}">{{ ucfirst($booking->status) }}</span></td>
                                            <td>
                                                <form action="{{ route('admin.configuration.bookings.age', $booking) }}" method="POST" class="d-flex align-items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="months" value="1" min="1" class="form-control form-control-sm" style="width: 75px;" title="Months to age by">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="set_completed" id="set_completed_{{ $booking->id }}" value="1">
                                                        <label class="form-check-label small" for="set_completed_{{ $booking->id }}">
                                                            Set Done
                                                        </label>
                                                    </div>
                                                    <button type="submit" class="btn btn-sm btn-warning" title="Age this booking's created_at date">
                                                        <i class="fas fa-hourglass-start me-1"></i>Age
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">No bookings found matching your criteria.</div>
                    @endif
                </div>
            </div>
            <!-- End Booking Time Shifter Section -->

            <!-- Placeholder for other configuration tools -->
            <!-- 
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-cogs me-1"></i>
                    Other Configuration Tool
                </div>
                <div class="card-body">
                    <p>Future configuration options will go here.</p>
                </div>
            </div>
            -->
        @endif
    </div>
</x-adminLayout>
