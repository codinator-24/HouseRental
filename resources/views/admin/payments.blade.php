<x-adminLayout>
    <div class="main-content" id="mainContent">
        <h1>Manage Payments</h1>
        <p>Oversee and manage all payment records.</p>

        <!-- Custom Tab Navigation -->
        <ul class="nav nav-tabs custom-tabs mb-3" id="paymentMethodTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="credit-payments-tab" data-bs-toggle="tab"
                    data-bs-target="#credit-payments" type="button" role="tab" aria-controls="credit-payments"
                    aria-selected="true">
                    <i class="bi bi-credit-card"></i> Credit Payments
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cash-payments-tab" data-bs-toggle="tab" data-bs-target="#cash-payments"
                    type="button" role="tab" aria-controls="cash-payments" aria-selected="false">
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
                                <option value="paying">Paying</option> {{-- Matches "paying" in JS --}}
                                <option value="paid">Paid</option> {{-- Matches "paid" or "succeeded" in JS --}}
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content" id="paymentMethodTabContent">
                        <!-- Credit Payments Tab Pane -->
                        <div class="tab-pane fade show active" id="credit-payments" role="tabpanel"
                            aria-labelledby="credit-payments-tab">
                            @php
                                $creditPayments = $payments->filter(function ($p) {
                                    $method = strtolower($p->payment_method);
                                    return $method === 'credit' || $method === 'credit_card';
                                });
                            @endphp
                            <div class="card custom-table">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="creditPaymentsTable"
                                        style="font-size: 12px;">
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
                                                    <td>{{ $payment->agreement->booking->tenant->full_name ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $payment->agreement->booking->house->title ?? 'N/A' }}</td>
                                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge 
                                                            @if ($payment->status == 'paid' || $payment->status == 'paid') bg-success 
                                                            @elseif($payment->status == 'paying') bg-warning text-dark
                                                            @elseif($payment->status == 'failed') bg-danger
                                                            @else bg-secondary @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') : 'N/A' }}
                                                    </td>
                                                    <td>{{ Str::limit($payment->notes, 30) ?: 'N/A' }}</td>
                                                    <td class="action-buttons">
                                                        <a href="#" class="btn btn-sm btn-info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewPaymentModal-credit-{{ $payment->id }}">
                                                            View
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-primary ms-1 treat-credit-payment-btn"
                                                            data-payment-id="{{ $payment->id }}"
                                                            data-landlord-name="{{ $payment->agreement->landlord->full_name ?? 'N/A' }}"
                                                            data-key-delivery-deadline="{{ $payment->agreement->key_delivery_deadline ? \Carbon\Carbon::parse($payment->agreement->key_delivery_deadline)->format('Y-m-d') : '' }}"
                                                            data-key-handed-over="{{ $payment->agreement->landlord_keys_delivered }}">
                                                            Treat
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- Payment Detail Modal for Credit Payment -->
                                                <div class="modal fade"
                                                    id="viewPaymentModal-credit-{{ $payment->id }}" tabindex="-1"
                                                    aria-labelledby="paymentModalLabel-credit-{{ $payment->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header text-center">
                                                                <h5 class="modal-title w-100"
                                                                    id="paymentModalLabel-credit-{{ $payment->id }}">
                                                                    Payment Details ID- {{ $payment->id }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <dl class="row justify-content-center">
                                                                    <dt class="col-sm-4">Agreement ID</dt>
                                                                    <dd class="col-sm-8">{{ $payment->agreement_id }}
                                                                    </dd>
                                                                    <dt class="col-sm-4">Tenant</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $payment->agreement->booking->tenant->full_name ?? 'N/A' }}
                                                                    </dd>
                                                                    <dt class="col-sm-4">House Title</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $payment->agreement->booking->house->title ?? 'N/A' }}
                                                                    </dd>
                                                                    <dt class="col-sm-4">Amount</dt>
                                                                    <dd class="col-sm-8">
                                                                        ${{ number_format($payment->amount, 2) }}</dd>
                                                                    <dt class="col-sm-4">Payment Method</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                                    </dd>
                                                                    <dt class="col-sm-4">Status</dt>
                                                                    <dd class="col-sm-8">
                                                                        <span
                                                                            class="badge @if ($payment->status == 'paid' || $payment->status == 'paid') bg-success @elseif($payment->status == 'paying') bg-warning text-dark @elseif($payment->status == 'failed') bg-danger @else bg-secondary @endif">
                                                                            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                                                        </span>
                                                                    </dd>
                                                                    <dt class="col-sm-4">Paid At</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') : 'N/A' }}
                                                                    </dd>
                                                                    <dt class="col-sm-4">Notes</dt>
                                                                    <dd class="col-sm-8">{{ $payment->notes ?: 'N/A' }}
                                                                    </dd>
                                                                </dl>
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                <button class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">No credit payments found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Landlord Actions Section for Credit Payments -->
                            <div class="mt-4 p-3 bg-light rounded-3 shadow-sm d-none" id="creditPaymentLandlordActionSection">
                                <h4 class="mb-4 border-bottom pb-2">
                                    <i class="bi bi-shield-lock-fill me-2"></i>Landlord Transaction Tracking
                                </h4>
                                <div class="row g-4 justify-content-center"> <!-- Centering the single card -->
                                    <div class="col-lg-6"> <!-- Or col-lg-8 for wider, or remove col-lg-X for full width on smaller screens -->
                                        <div class="card h-100 border-success border-2">
                                            <div class="card-header bg-success text-white">
                                                <h5 class="mb-0">
                                                    <i class="bi bi-key-fill me-2"></i>Landlord: <span id="creditLandlordNameDisplay"></span>
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-title fs-6 mb-3">
                                                    Manage actions related to the key handover.
                                                </p>
                                                <div class="mb-4">
                                                    <label for="creditKeyDeadline" class="form-label fw-bold">Key Handover Deadline</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                                        <input type="date" id="creditKeyDeadline" class="form-control">
                                                    </div>
                                                    <small class="form-text text-muted mt-1">Set the final date for the landlord to provide the key.</small>
                                                </div>
                                                <div class="form-check form-switch fs-5">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="creditKeyCheck">
                                                    <label class="form-check-label" for="creditKeyCheck">Key Handed Over</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12 text-end">
                                        <button type="button" class="btn btn-secondary me-2" id="cancelCreditLandlordAction">Cancel</button>
                                        <button type="button" class="btn btn-success" id="saveCreditLandlordDetailsBtn"><i class="bi bi-check-circle-fill me-2"></i>Save Changes</button>
                                    </div>
                                </div>
                            </div>
                            <!-- [END] Landlord Actions Section for Credit Payments -->
                        </div>

                        <!-- Cash Payments Tab Pane -->
                        <div class="tab-pane fade" id="cash-payments" role="tabpanel"
                            aria-labelledby="cash-payments-tab">
                            @php
                                $cashPayments = $payments->filter(function ($p) {
                                    return strtolower($p->payment_method) === 'cash';
                                });
                            @endphp
                            <div class="card custom-table">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="cashPaymentsTable"
                                        style="font-size: 12px;">
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
                                                    <td>{{ $payment->agreement->booking->tenant->full_name ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $payment->agreement->booking->house->title ?? 'N/A' }}</td>
                                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge 
                                                            @if ($payment->status == 'paid' || $payment->status == 'paid') bg-success 
                                                            @elseif($payment->status == 'paying') bg-warning text-dark
                                                            @elseif($payment->status == 'failed') bg-danger
                                                            @else bg-secondary @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') : 'N/A' }}
                                                    </td>
                                                    <td>{{ Str::limit($payment->notes, 30) ?: 'N/A' }}</td>
                                                    <td class="action-buttons">
                                                        <a href="#" class="btn btn-sm btn-info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewPaymentModal-cash-{{ $payment->id }}">
                                                            View
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-primary ms-1 treat-cash-payment-btn"
                                                            data-payment-id="{{ $payment->id }}"
                                                            data-tenant-name="{{ $payment->agreement->tenant->full_name ?? 'N/A' }}"
                                                            data-landlord-name="{{ $payment->agreement->landlord->full_name ?? 'N/A' }}"
                                                            data-payment-deadline="{{ $payment->payment_deadline ? $payment->payment_deadline->format('Y-m-d') : '' }}"
                                                            data-key-delivery-deadline="{{ $payment->agreement->key_delivery_deadline ? \Carbon\Carbon::parse($payment->agreement->key_delivery_deadline)->format('Y-m-d') : '' }}"
                                                            data-payment-status="{{ $payment->status }}"
                                                            data-key-handed-over="{{ $payment->agreement->landlord_keys_delivered }}"
                                                            data-agreement-id="{{ $payment->agreement_id }}">
                                                            Treat
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- Payment Detail Modal for Cash Payment -->
                                                <div class="modal fade"
                                                    id="viewPaymentModal-cash-{{ $payment->id }}" tabindex="-1"
                                                    aria-labelledby="paymentModalLabel-cash-{{ $payment->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header text-center">
                                                                <h5 class="modal-title w-100"
                                                                    id="paymentModalLabel-cash-{{ $payment->id }}">
                                                                    Payment Details ID- {{ $payment->id }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <dl class="row justify-content-center">
                                                                    <dt class="col-sm-4">Agreement ID</dt>
                                                                    <dd class="col-sm-8">{{ $payment->agreement_id }}
                                                                    </dd>
                                                                    <dt class="col-sm-4">Tenant</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $payment->agreement->booking->tenant->full_name ?? 'N/A' }}
                                                                    </dd>
                                                                    <dt class="col-sm-4">House Title</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $payment->agreement->booking->house->title ?? 'N/A' }}
                                                                    </dd>
                                                                    <dt class="col-sm-4">Amount</dt>
                                                                    <dd class="col-sm-8">
                                                                        ${{ number_format($payment->amount, 2) }}</dd>
                                                                    <dt class="col-sm-4">Payment Method</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                                    </dd>
                                                                    <dt class="col-sm-4">Status</dt>
                                                                    <dd class="col-sm-8">
                                                                        <span
                                                                            class="badge @if ($payment->status == 'paid' || $payment->status == 'paid') bg-success @elseif($payment->status == 'paying') bg-warning text-dark @elseif($payment->status == 'failed') bg-danger @else bg-secondary @endif">
                                                                            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                                                        </span>
                                                                    </dd>
                                                                    <dt class="col-sm-4">Paid At</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') : 'N/A' }}
                                                                    </dd>
                                                                    <dt class="col-sm-4">Notes</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $payment->notes ?: 'N/A' }}</dd>
                                                                </dl>
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                <button class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">No cash payments found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Cash Payment Management Section -->
                            <div class="mt-4 p-3 bg-light rounded-3 shadow-sm d-none" id="cashPaymentManagementSection">
                                <h4 class="mb-4 border-bottom pb-2">
                                    <i class="bi bi-wallet2 me-2"></i>Cash Transaction Tracking
                                </h4>

                                <div class="row g-4">
                                    <!-- Tenant-Facing Actions -->
                                    <div class="col-lg-6">
                                        <div class="card h-100 border-primary border-2">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0">
                                                    <i class="bi bi-person-check-fill me-2"></i>Tenant: <span id="tenantNameDisplay"></span>
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-title fs-6 mb-3">
                                                    Manage actions related to the tenant's payment.
                                                </p>

                                                <!-- Cash Payment Deadline -->
                                                <div class="mb-4">
                                                    <label for="cashPaymentDeadline"
                                                        class="form-label fw-bold">Payment Deadline</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="bi bi-calendar-event"></i></span>
                                                        <input type="date" id="cashPaymentDeadline"
                                                            class="form-control">
                                                    </div>
                                                    <small class="form-text text-muted mt-1">Set the final date for the
                                                        tenant to pay in cash.</small>
                                                </div>

                                                <!-- Cash Received Checkbox -->
                                                <div class="form-check form-switch fs-5">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="cashPaymentCheck">
                                                    <label class="form-check-label" for="cashPaymentCheck">
                                                        Cash Payment Received
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Landlord-Facing Actions -->
                                    <div class="col-lg-6">
                                        <div class="card h-100 border-success border-2">
                                            <div class="card-header bg-success text-white">
                                                <h5 class="mb-0">
                                                    <i class="bi bi-key-fill me-2"></i>Landlord: <span id="landlordNameDisplay"></span>
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-title fs-6 mb-3">
                                                    Manage actions related to the key handover.
                                                </p>

                                                <!-- Key Handover Deadline -->
                                                <div class="mb-4">
                                                    <label for="keyDeadline" class="form-label fw-bold">Key Handover
                                                        Deadline</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="bi bi-calendar-event"></i></span>
                                                        <input type="date" id="keyDeadline" class="form-control">
                                                    </div>
                                                    <small class="form-text text-muted mt-1">Set the final date for the
                                                        landlord to provide the key.</small>
                                                </div>

                                                <!-- Key Received Checkbox -->
                                                <div class="form-check form-switch fs-5">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="keyCheck">
                                                    <label class="form-check-label" for="keyCheck">
                                                        Key Handed Over
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12 text-end">
                                        <button type="button" class="btn btn-secondary me-2" id="cancelCashManagement">Cancel</button>
                                        <button type="button" class="btn btn-success" id="saveCashDetailsBtn"><i
                                                class="bi bi-check-circle-fill me-2"></i>Save Changes</button>
                                    </div>
                                </div>
                            </div>
                            <!-- [END] Cash Payment Management Section -->
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
                            rowStatusText = statusCell.querySelector('span.badge').textContent.trim()
                                .toLowerCase();
                        }

                        let matchesSearch = textContent.includes(searchTerm);
                        let matchesStatus = false;

                        if (selectedStatus === "") { // "All Statuses"
                            matchesStatus = true;
                        } else if (selectedStatus === "paying") {
                            matchesStatus = (rowStatusText === "paying");
                        } else if (selectedStatus === "paid") {
                            matchesStatus = (rowStatusText === "paid" || rowStatusText === "succeeded" ||
                                rowStatusText === "paid");
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

                // Cash Payment Management Section Logic
                const cashManagementSection = document.getElementById('cashPaymentManagementSection');
                const tenantNameDisplay = document.getElementById('tenantNameDisplay');
                const landlordNameDisplay = document.getElementById('landlordNameDisplay');
                const cashPaymentDeadlineInput = document.getElementById('cashPaymentDeadline');
                const keyDeadlineInput = document.getElementById('keyDeadline');
                const cashPaymentCheck = document.getElementById('cashPaymentCheck');
                const keyCheck = document.getElementById('keyCheck');
                const cancelCashManagementBtn = document.getElementById('cancelCashManagement');
                const saveCashDetailsBtn = document.getElementById('saveCashDetailsBtn');

                // Credit Payment Landlord Action Section Elements
                const creditLandlordActionSection = document.getElementById('creditPaymentLandlordActionSection');
                const creditLandlordNameDisplay = document.getElementById('creditLandlordNameDisplay');
                const creditKeyDeadlineInput = document.getElementById('creditKeyDeadline');
                const creditKeyCheck = document.getElementById('creditKeyCheck');
                const cancelCreditLandlordActionBtn = document.getElementById('cancelCreditLandlordAction');
                const saveCreditLandlordDetailsBtn = document.getElementById('saveCreditLandlordDetailsBtn');

                // CSRF Token for AJAX
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');


                document.querySelectorAll('.treat-cash-payment-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        // Retrieve data from button
                        const tenantName = this.dataset.tenantName;
                        const landlordName = this.dataset.landlordName;
                        const paymentDeadline = this.dataset.paymentDeadline;
                        const keyDeliveryDeadline = this.dataset.keyDeliveryDeadline;
                        const paymentStatus = this.dataset.paymentStatus.toLowerCase();
                        const keyHandedOver = this.dataset.keyHandedOver;
                        const paymentId = this.dataset.paymentId;

                        // Populate the section
                        if (saveCashDetailsBtn) saveCashDetailsBtn.dataset.paymentId = paymentId; // Store paymentId on save button
                        if (tenantNameDisplay) tenantNameDisplay.textContent = tenantName;
                        if (landlordNameDisplay) landlordNameDisplay.textContent = landlordName;
                        if (cashPaymentDeadlineInput) cashPaymentDeadlineInput.value = paymentDeadline;
                        if (keyDeadlineInput) keyDeadlineInput.value = keyDeliveryDeadline;

                        // Set checkbox states
                        if (cashPaymentCheck) {
                            cashPaymentCheck.checked = (paymentStatus === 'paid' || paymentStatus === 'paid');
                        }
                        if (keyCheck) {
                            keyCheck.checked = (keyHandedOver === '1');
                        }

                        // Show the section
                        if (cashManagementSection) {
                            cashManagementSection.classList.remove('d-none');
                            cashManagementSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                });

                if (cancelCashManagementBtn && cashManagementSection) {
                    cancelCashManagementBtn.addEventListener('click', function() {
                        cashManagementSection.classList.add('d-none');
                    });
                }

                // Event listeners for Credit Payment Landlord Actions
                document.querySelectorAll('.treat-credit-payment-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        // Retrieve data from button
                        const landlordName = this.dataset.landlordName;
                        const keyDeliveryDeadline = this.dataset.keyDeliveryDeadline;
                        const keyHandedOver = this.dataset.keyHandedOver;
                        const paymentId = this.dataset.paymentId; // Assuming credit treat buttons also get data-payment-id

                        // Populate the section
                        if (saveCreditLandlordDetailsBtn) saveCreditLandlordDetailsBtn.dataset.paymentId = paymentId; // Store paymentId
                        if (creditLandlordNameDisplay) creditLandlordNameDisplay.textContent = landlordName;
                        if (creditKeyDeadlineInput) creditKeyDeadlineInput.value = keyDeliveryDeadline;
                        if (creditKeyCheck) creditKeyCheck.checked = (keyHandedOver === '1');

                        // Show the section
                        if (creditLandlordActionSection) {
                            creditLandlordActionSection.classList.remove('d-none');
                            creditLandlordActionSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                });

                if (cancelCreditLandlordActionBtn && creditLandlordActionSection) {
                    cancelCreditLandlordActionBtn.addEventListener('click', function() {
                        creditLandlordActionSection.classList.add('d-none');
                    });
                }

                // Save Cash Details
                if (saveCashDetailsBtn) {
                    saveCashDetailsBtn.addEventListener('click', function() {
                        const paymentId = this.dataset.paymentId;
                        if (!paymentId) {
                            alert('Error: Payment ID not found.');
                            return;
                        }

                        const data = {
                            cash_payment_deadline: cashPaymentDeadlineInput.value,
                            cash_payment_received: cashPaymentCheck.checked,
                            key_delivery_deadline: keyDeadlineInput.value,
                            key_handed_over: keyCheck.checked,
                            _token: csrfToken
                        };

                        fetch(`/admin/payments/${paymentId}/update-cash-details`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                alert(result.message || 'Details updated successfully!');
                                if (cashManagementSection) cashManagementSection.classList.add('d-none');
                                // Optionally, refresh part of the page or update the table row dynamically
                                location.reload(); // Simple refresh for now
                            } else {
                                alert(result.message || 'Failed to update details.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while saving.');
                        });
                    });
                }

                // Save Credit Landlord Details
                if (saveCreditLandlordDetailsBtn) {
                    saveCreditLandlordDetailsBtn.addEventListener('click', function() {
                        const paymentId = this.dataset.paymentId;
                        if (!paymentId) {
                            alert('Error: Payment ID not found for credit details.');
                            return;
                        }

                        const data = {
                            credit_key_delivery_deadline: creditKeyDeadlineInput.value,
                            credit_key_handed_over: creditKeyCheck.checked,
                            _token: csrfToken
                        };

                        fetch(`/admin/payments/${paymentId}/update-credit-landlord-details`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                alert(result.message || 'Landlord details updated successfully!');
                                if (creditLandlordActionSection) creditLandlordActionSection.classList.add('d-none');
                                location.reload(); // Simple refresh for now
                            } else {
                                alert(result.message || 'Failed to update landlord details.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while saving landlord details.');
                        });
                    });
                }
            });
        </script>
    @endpush

</x-adminLayout>
