<x-adminLayout>
    <div class="main-content" id="mainContent">
        <h1>Dashboard</h1>
        <p>Overview of your application's statistics.</p>

        <div class="container mt-4">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 text-center">

                <div class="col">
                    <div class="card shadow rounded-4 p-3">
                        <i class="bi bi-person-vcard text-secondary"></i>
                        <h5 class="mt-2">Total Users</h5>
                        <h3>{{ $users }}</h3>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow rounded-4 p-3">
                        <i class="bi bi-person-exclamation text-danger"></i>
                        <h5 class="mt-2">Users Need Verify</h5>
                        <h3>{{ $verify }}</h3>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow rounded-4 p-3">
                        <i class="bi bi-check-circle text-info"></i>
                        <h5 class="mt-2">Houses Need Approve</h5>
                        <h3>{{ $aproves }}</h3>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow rounded-4 p-3">
                        <i class="bi bi-people" style="color:#900C3F;"></i>
                        <h5 class="mt-2">Both Users</h5>
                        <h3>{{ $bothes }}</h3>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow rounded-4 p-3">
                        <i class="bi bi-person-check text-success" style="color:#0f7a99;"></i>
                        <h5 class="mt-2">Landlord Users</h5>
                        <h3>{{ $landlords }}</h3>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow rounded-4 p-3">
                        <i class="bi bi-person-up text-primary"></i>
                        <h5 class="mt-2">Tenant Users</h5>
                        <h3>{{ $tenants }}</h3>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow rounded-4 p-3">
                        <i class="bi bi-house text-warning"></i>
                        <h5 class="mt-2">Total Houses</h5>
                        <h3>{{ $houses }}</h3>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow rounded-4 p-3">
                        <i class="bi bi-chat-dots text-dark"></i>
                        <h5 class="mt-2">Feedback</h5>
                        <h3>{{ $feedbacks }}</h3>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Add any dashboard-specific JavaScript here if needed --}}
    @endpush

</x-adminLayout>
