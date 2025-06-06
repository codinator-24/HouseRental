<x-adminLayout>
    <div class="main-content" id="mainContent">
        <h1>Manage Payments</h1>
        <p>Oversee and manage all payment records.</p>

        <div class="">
            <div class="container py-5">
                <div class="py-3">
                    {{-- Search and Filter Section --}}
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <h3 style="color:rgb(50, 149, 235);">Search For Payments</h3>
                            <input type="text" id="paymentSearchInput" class="form-control"
                                placeholder="Search by tenant, house, amount...">
                        </div>
                        <div class="col-md-4">
                            <h3 style="color:rgb(50, 149, 235);">Filter by Status</h3>
                            <select id="paymentStatusFilter" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending">Paying</option> {{-- Matches "pending" in JS --}}
                                <option value="paid">Paid</option> {{-- Matches "paid" or "succeeded" in JS --}}
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>

                    <div class="card custom-table">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="paymentTable" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Agreement ID</th>
                                        <th>Tenant</th>
                                        <th>House Title</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Paid At</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->id }}</td>
                                            <td>{{ $payment->agreement_id }}</td>
                                            <td>{{ $payment->agreement->booking->tenant->full_name ?? 'N/A' }}</td>
                                            <td>{{ $payment->agreement->booking->house->title ?? 'N/A' }}</td>
                                            <td>{{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                            <td>
                                                <span
                                                    class="badge 
                                                    @if ($payment->status == 'completed' || $payment->status == 'paid') bg-success 
                                                    @elseif($payment->status == 'pending') bg-warning text-dark
                                                    @elseif($payment->status == 'failed') bg-danger
                                                    @else bg-secondary @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') : 'N/A' }}
                                            </td>
                                            <td>{{ Str::limit($payment->notes, 30) ?: 'N/A' }}</td>
                                            <td class="action-buttons">
                                                <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewPaymentModal-{{ $payment->id }}">
                                                    View
                                                        </a>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No payments found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Detail Modal -->
<div class="modal fade" id="viewPaymentModal-{{ $payment->id }}" tabindex="-1" aria-labelledby="paymentModalLabel-{{ $payment->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Large modal -->
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title w-100" id="paymentModalLabel-{{ $payment->id }}">Payment Details  ID- {{ $payment->id }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <dl class="row justify-content-center">
          <dt class="col-sm-4">Agreement ID</dt>
          <dd class="col-sm-8">{{ $payment->agreement_id }}</dd>

          <dt class="col-sm-4">Tenant</dt>
          <dd class="col-sm-8">{{ $payment->agreement->booking->tenant->full_name ?? 'N/A' }}</dd>

          <dt class="col-sm-4">House Title</dt>
          <dd class="col-sm-8">{{ $payment->agreement->booking->house->title ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Amount</dt>
          <dd class="col-sm-8">${{ number_format($payment->amount, 2) }}</dd>

          <dt class="col-sm-4">Payment Method</dt>
          <dd class="col-sm-8">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</dd>

          <dt class="col-sm-4">Status</dt>
          <dd class="col-sm-8">
            <span class="badge 
              @if ($payment->status == 'completed' || $payment->status == 'paid') bg-success 
              @elseif($payment->status == 'pending') bg-warning text-dark
              @elseif($payment->status == 'failed') bg-danger
              @else bg-secondary @endif">
              {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
            </span>
          </dd>

          <dt class="col-sm-4">Paid At</dt>
          <dd class="col-sm-8">{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') : 'N/A' }}</dd>

          <dt class="col-sm-4">Notes</dt>
          <dd class="col-sm-8">{{ $payment->notes ?: 'N/A' }}</dd>
        </dl>
      </div>

      <div class="modal-footer justify-content-center">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('paymentSearchInput');
                const statusFilter = document.getElementById('paymentStatusFilter');
                const paymentTable = document.getElementById('paymentTable');

                if (searchInput && statusFilter && paymentTable) {
                    const paymentTableRows = paymentTable.querySelectorAll('tbody tr');

                    function filterPaymentsTable() {
                        const searchTerm = searchInput.value.toLowerCase();
                        const selectedStatus = statusFilter.value; // e.g., "pending", "paid", "failed", ""
                        let hasVisibleRows = false;

                        paymentTableRows.forEach(function(row) {
                            if (row.cells.length === 1 && row.cells[0].colSpan ===
                                10) { // "No payments found" row
                                return;
                            }

                            const textContent = (row.textContent || row.innerText).toLowerCase();
                            const statusCell = row.cells[6]; // Status is in the 7th column (index 6)
                            let rowStatusText = '';
                            if (statusCell && statusCell.querySelector('span.badge')) {
                                rowStatusText = statusCell.querySelector('span.badge').textContent.trim()
                                    .toLowerCase();
                            }

                            let matchesSearch = textContent.includes(searchTerm);
                            let matchesStatus = false;

                            if (selectedStatus === "") { // "All Statuses"
                                matchesStatus = true;
                            } else if (selectedStatus === "pending") { // "Paying (Pending)"
                                matchesStatus = (rowStatusText === "pending");
                            } else if (selectedStatus === "paid") { // "Paid"
                                matchesStatus = (rowStatusText === "paid" || rowStatusText === "succeeded");
                            } else if (selectedStatus === "failed") { // "Failed"
                                matchesStatus = (rowStatusText === "failed");
                            }

                            if (matchesSearch && matchesStatus) {
                                row.style.display = '';
                                hasVisibleRows = true;
                            } else {
                                row.style.display = 'none';
                            }
                        });

                        const noResultsRow = Array.from(paymentTableRows).find(
                            r => r.cells.length === 1 && r.cells[0].colSpan === 10
                        );
                        if (noResultsRow) {
                            noResultsRow.style.display = hasVisibleRows ? 'none' : '';
                        }
                    }

                    searchInput.addEventListener('keyup', filterPaymentsTable);
                    statusFilter.addEventListener('change', filterPaymentsTable);
                    filterPaymentsTable(); // Initial filter
                }
            });
        </script>
    @endpush

</x-adminLayout>
