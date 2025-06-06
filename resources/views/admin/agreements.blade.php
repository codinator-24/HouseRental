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
                                                            @if ($agreement->status == 'agreed') bg-success 
                                                            @elseif($agreement->status == 'expired') bg-danger
                                                            @elseif($agreement->status == 'pending' || $agreement->status == 'pending_signature') bg-warning text-dark
                                                            @else bg-secondary @endif">
                                                {{ ucfirst(str_replace('_', ' ', $agreement->status)) }}
                                            </span>
                                        </td>
                                        <td class="action-buttons">
                                            <!-- View Button -->
                                    <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewAgreementModal-{{ $agreement->id }}">
                                              View
                                            </a>
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

    <!-- Agreement Detail Modal -->
     
<div class="modal fade" id="viewAgreementModal-{{ $agreement->id }}" tabindex="-1" aria-labelledby="agreementModalLabel-{{ $agreement->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- large modal for better layout -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agreementModalLabel-{{ $agreement->id }}">Agreement Details ID -{{ $agreement->id }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <dl class="row">
          <dt class="col-sm-4">Tenant</dt>
          <dd class="col-sm-8">{{ $agreement->booking->tenant->full_name ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Landlord</dt>
          <dd class="col-sm-8">{{ $agreement->booking->house->landlord->full_name ?? 'N/A' }}</dd>

          <dt class="col-sm-4">House Title</dt>
          <dd class="col-sm-8">{{ $agreement->booking->house->title ?? 'N/A' }}</dd>

          <dt class="col-sm-4">City</dt>
          <dd class="col-sm-8">{{ $agreement->booking->house->city ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Signed At</dt>
          <dd class="col-sm-8">{{ $agreement->signed_at ? \Carbon\Carbon::parse($agreement->signed_at)->format('Y-m-d') : 'N/A' }}</dd>

          <dt class="col-sm-4">Expires At</dt>
          <dd class="col-sm-8">{{ $agreement->expires_at ? \Carbon\Carbon::parse($agreement->expires_at)->format('Y-m-d') : 'N/A' }}</dd>

          <dt class="col-sm-4">Rent Amount</dt>
          <dd class="col-sm-8">${{ number_format($agreement->rent_amount, 2) }}</dd>

          <dt class="col-sm-4">Status</dt>
          <dd class="col-sm-8">
            <span class="badge 
              @if ($agreement->status == 'agreed') bg-success 
              @elseif($agreement->status == 'reject') bg-danger
              @elseif($agreement->status == 'pending' || $agreement->status == 'pending_signature') bg-warning text-dark
              @else bg-secondary @endif">
              {{ ucfirst(str_replace('_', ' ', $agreement->status)) }}
            </span>
          </dd>
        </dl>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
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

</x-adminLayout>
