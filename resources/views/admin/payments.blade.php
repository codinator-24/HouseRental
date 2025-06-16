<x-adminLayout>
    <div class="main-content" id="mainContent">
        <h1>Manage Payments</h1>
        <p>Oversee and manage all payment records.</p>

        <!-- Custom Tab Navigation -->
        <ul class="nav nav-tabs custom-tabs mb-3" id="paymentMethodTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="credit-payments-tab" data-bs-toggle="tab" data-bs-target="#credit-payments" type="button" role="tab" aria-controls="credit-payments" aria-selected="true">
                    <i class="bi bi-credit-card"></i> Credit Payments
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cash-payments-tab" data-bs-toggle="tab" data-bs-target="#cash-payments" type="button" role="tab" aria-controls="cash-payments" aria-selected="false">
                    <i class="bi bi-cash"></i> Cash Payments
                </button>
            </li>
        </ul>

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

                    <!-- Tab Content -->
                    <div class="tab-content" id="paymentMethodTabContent">
                        <!-- Credit Payments Tab Pane -->
                        <div class="tab-pane fade show active" id="credit-payments" role="tabpanel" aria-labelledby="credit-payments-tab">
                            @php 
                                $creditPayments = $payments->filter(function($p) { 
                                    $method = strtolower($p->payment_method);
                                    return $method === 'credit' || $method === 'credit_card'; 
                                });
                            @endphp
                            <div class="card custom-table">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="creditPaymentsTable" style="font-size: 12px;">
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
                                            @forelse ($creditPayments as $payment)
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
                                                    <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                                                    <td>{{ Str::limit($payment->notes, 30) ?: 'N/A' }}</td>
                                                    <td class="action-buttons">
                                                        <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewPaymentModal-credit-{{ $payment->id }}">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                                <!-- Payment Detail Modal for Credit Payment -->
                                                <div class="modal fade" id="viewPaymentModal-credit-{{ $payment->id }}" tabindex="-1" aria-labelledby="paymentModalLabel-credit-{{ $payment->id }}" aria-hidden="true">
                                                  <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                      <div class="modal-header text-center">
                                                        <h5 class="modal-title w-100" id="paymentModalLabel-credit-{{ $payment->id }}">Payment Details ID- {{ $payment->id }}</h5>
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
                                                            <span class="badge @if ($payment->status == 'completed' || $payment->status == 'paid') bg-success @elseif($payment->status == 'pending') bg-warning text-dark @elseif($payment->status == 'failed') bg-danger @else bg-secondary @endif">
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
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">No credit payments found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Cash Payments Tab Pane -->
                        <div class="tab-pane fade" id="cash-payments" role="tabpanel" aria-labelledby="cash-payments-tab">
                            @php 
                                $cashPayments = $payments->filter(function($p) { 
                                    return strtolower($p->payment_method) === 'cash'; 
                                });
                            @endphp
                            <div class="card custom-table">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="cashPaymentsTable" style="font-size: 12px;">
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
                                            @forelse ($cashPayments as $payment)
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
                                                    <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                                                    <td>{{ Str::limit($payment->notes, 30) ?: 'N/A' }}</td>
                                                    <td class="action-buttons">
                                                        <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewPaymentModal-cash-{{ $payment->id }}">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                                <!-- Payment Detail Modal for Cash Payment -->
                                                <div class="modal fade" id="viewPaymentModal-cash-{{ $payment->id }}" tabindex="-1" aria-labelledby="paymentModalLabel-cash-{{ $payment->id }}" aria-hidden="true">
                                                  <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                      <div class="modal-header text-center">
                                                        <h5 class="modal-title w-100" id="paymentModalLabel-cash-{{ $payment->id }}">Payment Details ID- {{ $payment->id }}</h5>
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
                                                            <span class="badge @if ($payment->status == 'completed' || $payment->status == 'paid') bg-success @elseif($payment->status == 'pending') bg-warning text-dark @elseif($payment->status == 'failed') bg-danger @else bg-secondary @endif">
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
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">No cash payments found.</td>
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
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('paymentSearchInput');
                const statusFilter = document.getElementById('paymentStatusFilter');
                const creditPaymentsTable = document.getElementById('creditPaymentsTable');
                const cashPaymentsTable = document.getElementById('cashPaymentsTable');
                const tabs = document.querySelectorAll('#paymentMethodTab button[data-bs-toggle="tab"]');

                function filterPaymentsTable(tableElement) {
                    if (!tableElement) return;

                    const paymentTableRows = tableElement.querySelectorAll('tbody tr');
                    const searchTerm = searchInput.value.toLowerCase();
                    const selectedStatus = statusFilter.value;
                    let hasVisibleRows = false;
                    
                    // This will count rows that are actual data, not "no results" rows
                    let dataRowsInTableCount = 0; 
                    paymentTableRows.forEach(row => {
                        if (!(row.cells.length === 1 && row.cells[0].colSpan === 10)) {
                            dataRowsInTableCount++;
                        }
                    });

                    paymentTableRows.forEach(function(row) {
                        // Check if it's a "no results" row by checking colspan on its first td
                        const firstCell = row.cells[0];
                        if (firstCell && firstCell.colSpan === 10) {
                            // This row is a "No X payments found" row, initially hide it if there are data rows.
                            // It will be shown later by specific logic if all data rows get hidden by filters.
                            row.style.display = dataRowsInTableCount > 0 ? 'none' : ''; 
                            return;
                        }

                        const textContent = (row.textContent || row.innerText).toLowerCase();
                        const statusCell = row.cells[6]; // Status is in the 7th column (index 6)
                        let rowStatusText = '';
                        if (statusCell && statusCell.querySelector('span.badge')) {
                            rowStatusText = statusCell.querySelector('span.badge').textContent.trim().toLowerCase();
                        }

                        let matchesSearch = textContent.includes(searchTerm);
                        let matchesStatus = false;

                        if (selectedStatus === "") { // "All Statuses"
                            matchesStatus = true;
                        } else if (selectedStatus === "pending") {
                            matchesStatus = (rowStatusText === "pending");
                        } else if (selectedStatus === "paid") {
                            matchesStatus = (rowStatusText === "paid" || rowStatusText === "succeeded");
                        } else if (selectedStatus === "failed") {
                            matchesStatus = (rowStatusText === "failed");
                        }

                        if (matchesSearch && matchesStatus) {
                            row.style.display = '';
                            hasVisibleRows = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });
                    
                    const noResultsRowForThisTable = Array.from(tableElement.querySelectorAll('tbody tr')).find(
                        r => r.cells.length === 1 && r.cells[0].colSpan === 10
                    );

                    if (noResultsRowForThisTable) {
                        if (dataRowsInTableCount > 0 && !hasVisibleRows) {
                            // Data rows existed, but all were filtered out
                            noResultsRowForThisTable.style.display = '';
                        } else if (dataRowsInTableCount === 0) {
                            // No data rows to begin with for this tab (Blade rendered "No X payments")
                            noResultsRowForThisTable.style.display = '';
                        } else if (hasVisibleRows) {
                            // Data rows are visible, so hide the "No X payments" row
                            noResultsRowForThisTable.style.display = 'none';
                        }
                    }
                }

                function getActiveTable() {
                    const activeTabPane = document.querySelector('#paymentMethodTabContent .tab-pane.fade.show.active');
                    if (activeTabPane) {
                        if (activeTabPane.id === 'credit-payments') return creditPaymentsTable;
                        if (activeTabPane.id === 'cash-payments') return cashPaymentsTable;
                    }
                    return null;
                }

                if (searchInput) {
                    searchInput.addEventListener('keyup', () => filterPaymentsTable(getActiveTable()));
                }
                if (statusFilter) {
                    statusFilter.addEventListener('change', () => filterPaymentsTable(getActiveTable()));
                }

                tabs.forEach(tab => {
                    tab.addEventListener('shown.bs.tab', function() {
                        filterPaymentsTable(getActiveTable());
                    });
                });

                // Initial filter for the default active tab (credit payments)
                filterPaymentsTable(creditPaymentsTable);
            });
        </script>
    @endpush

</x-adminLayout>
