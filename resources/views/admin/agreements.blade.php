<x-adminLayout>
    {{-- The wrapping div with class "main-content" has been removed. 
         The <main class="main-content"> tag in adminLayout.blade.php now serves this purpose. --}}
    <h1>Manage Agreements</h1>
    <p>Oversee and manage all rental agreements.</p>

    <div class="">
        <div class="container py-5">
            <div class="py-3">
                {{-- Search and Filter Section --}}
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h3 style="color:rgb(50, 149, 235);">Search For Agreements</h3>
                        <input type="text" id="agreementSearchInput" class="form-control"
                            placeholder="Search agreements by tenant, landlord, house, status...">
                    </div>
                    <div class="col-md-4">
                        <h3 style="color:rgb(50, 149, 235);">Filter by Status</h3>
                        <select id="statusFilter" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="agreed">Agreed</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                </div>

                <div class="card custom-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="agreementTable" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tenant</th>
                                    <th>Landlord</th>
                                    <th>House Title</th>
                                    <th>City</th>
                                    <th>Signed At</th>
                                    <th>Expires At</th>
                                    <th>Rent Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($agreements as $agreement)
                                    <tr>
                                        <td>{{ $agreement->id }}</td>
                                        <td>{{ $agreement->booking->tenant->full_name ?? 'N/A' }}</td>
                                        <td>{{ $agreement->booking->house->landlord->full_name ?? 'N/A' }}
                                        </td>
                                        <td>{{ $agreement->booking->house->title ?? 'N/A' }}</td>
                                        <td>{{ $agreement->booking->house->city ?? 'N/A' }}</td>
                                        <td>{{ $agreement->signed_at ? \Carbon\Carbon::parse($agreement->signed_at)->format('Y-m-d') : 'N/A' }}
                                        </td>
                                        <td>{{ $agreement->expires_at ? \Carbon\Carbon::parse($agreement->expires_at)->format('Y-m-d') : 'N/A' }}
                                        </td>
                                        <td>{{ number_format($agreement->rent_amount, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge 
                                                            @if ($agreement->status == 'active') bg-success 
                                                            @elseif($agreement->status == 'expired') bg-danger
                                                            @elseif($agreement->status == 'pending' || $agreement->status == 'pending_signature') bg-warning text-dark
                                                            @else bg-secondary @endif">
                                                {{ ucfirst(str_replace('_', ' ', $agreement->status)) }}
                                            </span>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="#" class="btn btn-sm btn-info">View</a>
                                            {{-- Add other actions like edit/delete if needed --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No agreements found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- End of Agreements Table Section --}}
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('agreementSearchInput');
                const statusFilter = document.getElementById('statusFilter');
                const agreementTable = document.getElementById('agreementTable');

                if (searchInput && statusFilter && agreementTable) {
                    const agreementTableRows = agreementTable.querySelectorAll('tbody tr');

                    function filterTable() {
                        const searchTerm = searchInput.value.toLowerCase();
                        const selectedStatus = statusFilter.value;
                        let hasVisibleRows = false;

                        agreementTableRows.forEach(function(row) {
                            // Handle the "No agreements found" row separately
                            if (row.cells.length === 1 && row.cells[0].colSpan === 10) {
                                // This is likely the "No agreements found" row, hide it during filtering
                                // It will be shown/hidden based on whether other rows are visible
                                return;
                            }

                            const textContent = (row.textContent || row.innerText).toLowerCase();
                            const statusCell = row.cells[8]; // Status is in the 9th column (index 8)
                            let rowStatusText = '';

                            if (statusCell && statusCell.querySelector('span.badge')) {
                                rowStatusText = statusCell.querySelector('span.badge').textContent.trim()
                                    .toLowerCase();
                            }

                            let matchesSearch = textContent.includes(searchTerm);
                            let matchesStatus = false;

                            if (selectedStatus === "") { // "All Statuses"
                                matchesStatus = true;
                            } else if (selectedStatus === "pending") {
                                matchesStatus = (rowStatusText === "pending" || rowStatusText ===
                                    "pending signature");
                            } else if (selectedStatus === "agreed") {
                                // Assuming "agreed" covers "active" status from your badge logic
                                matchesStatus = (rowStatusText === "agreed" || rowStatusText === "active");
                            } else if (selectedStatus === "expired") {
                                matchesStatus = (rowStatusText === "expired");
                            }
                            // Add more specific status checks if needed, e.g., for "pending_signature"
                            // else if (selectedStatus === "pending_signature") {
                            //     matchesStatus = (rowStatusText === "pending signature");
                            // }


                            if (matchesSearch && matchesStatus) {
                                row.style.display = '';
                                hasVisibleRows = true;
                            } else {
                                row.style.display = 'none';
                            }
                        });

                        // Show or hide the "No agreements found" row
                        const noResultsRow = Array.from(agreementTableRows).find(
                            r => r.cells.length === 1 && r.cells[0].colSpan === 10
                        );
                        if (noResultsRow) {
                            noResultsRow.style.display = hasVisibleRows ? 'none' : '';
                        }
                    }

                    searchInput.addEventListener('keyup', filterTable);
                    statusFilter.addEventListener('change', filterTable);
                    filterTable
                (); // Initial filter application in case of pre-filled values or to handle "no results" row
                }
            });
        </script>
    @endpush

<<<<<<< HEAD
</x-adminLayout>
=======

        const searchInput = document.getElementById('agreementSearchInput');
        const statusFilter = document.getElementById('statusFilter');
        const agreementTableRows = document.querySelectorAll('#agreementTable tbody tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedStatus = statusFilter.value;

            agreementTableRows.forEach(function(row) {
                const textContent = (row.textContent || row.innerText).toLowerCase();
                // Assuming the status is in the 9th column (index 8)
                // and the badge span is the first child of the td
                const statusCell = row.cells[8]; 
                let rowStatusText = '';
                if (statusCell && statusCell.querySelector('span.badge')) {
                    rowStatusText = statusCell.querySelector('span.badge').textContent.trim().toLowerCase();
                }

                let matchesSearch = textContent.includes(searchTerm);
                let matchesStatus = false;

                if (selectedStatus === "") { // "All Statuses"
                    matchesStatus = true;
                } else if (selectedStatus === "pending") {
                    matchesStatus = (rowStatusText === "pending" || rowStatusText === "pending signature");
                } else if (selectedStatus === "agreed") {
                    matchesStatus = (rowStatusText === "agreed");
                } else if (selectedStatus === "expired") {
                    matchesStatus = (rowStatusText === "expired");
                }

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('keyup', filterTable);
        statusFilter.addEventListener('change', filterTable);


    </script>
    {{-- Bootstrap JS bundle is generally good to keep if other Bootstrap components are used in your layout or might be added later. --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
>>>>>>> b439fc903b6d3b26c31bc7c8b30e2c0e91572b44
