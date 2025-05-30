<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Agreements & Payments</title>
    <link rel="shortcut icon" type="image" href="./images/logo.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet" />

    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #f4f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .sidebar {
            width: 235px;
            /* Adjusted width */
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-image: linear-gradient(to right, rgb(50, 149, 235), rgb(73, 185, 219));
            color: white;
            padding-top: 1rem;
            transition: width 0.4s ease;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 90px;
            /* Adjusted collapsed width */
        }

        .sidebar .nav-link {
            color: #ffffff;
            white-space: nowrap;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link i {
            font-size: 1.25rem;
            margin-right: 10px;
            min-width: 20px;
            text-align: center;
        }

        .sidebar .nav-link span {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .toggle-wrapper {
            padding: 0.75rem 1rem;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: white;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            padding: 0;
            margin: 0;
        }

        .main-content {
            margin-left: 250px;
            /* Adjusted margin */
            padding: 2rem 2.5rem;
            transition: margin-left 0.4s ease;
            min-height: 100vh;
            background-color: #fff;
            box-shadow: 0 0 15px rgb(0 0 0 / 0.05);
            border-radius: 12px;
            margin-top: 1rem;
            margin-bottom: 2rem;
            width: calc(100% - 270px);
            /* Adjusted width */
            margin-right: 20px;
        }

        .sidebar.collapsed~.main-content {
            margin-left: 100px;
            /* Adjusted margin */
            width: calc(100% - 120px);
            /* Adjusted width */
        }

        .card i {
            font-size: 2rem;
        }

        .card {
            border: none;
        }

        .nav-link.activee {
            background-color: rgba(7, 82, 112, 0.56);
            color: white;
            font-weight: 600;
            border-radius: 6px;
            width: calc(100% - 10px);
            /* Adjusted width for active link */
            margin-left: 5px;
            margin-right: 5px;
        }

        .sidebar.collapsed .nav-link.activee {
            width: 60px;
            /* Fixed width for collapsed active link */
        }


        .custom-table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: white;
            border: none;
        }

        .table {
            margin-bottom: 0;
            font-size: 14px;
        }

        .table thead th {
            background: linear-gradient(to right, rgb(50, 149, 235), rgb(73, 185, 219));
            color: white;
            font-weight: 600;
            padding: 15px 12px;
            border: none;
            font-size: 13px;
        }

        .table tbody td {
            padding: 12px;
            border: none;
            vertical-align: middle;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background-color: rgba(50, 149, 235, 0.05);
            transition: background-color 0.3s ease;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table tbody tr:nth-child(even):hover {
            background-color: rgba(50, 149, 235, 0.08);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-delete {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        /* Tab Styles */
        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
            color: #495057;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            font-weight: bold;
        }
        .tab-content {
            border: 1px solid #dee2e6;
            border-top: 0;
            padding: 1.5rem;
            background-color: #fff;
            border-bottom-left-radius: .25rem;
            border-bottom-right-radius: .25rem;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <nav class="sidebar" id="sidebar">
            <div class="toggle-wrapper">
                <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            <ul class="nav nav-pills flex-column w-100">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('AdminDashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('houses') }}">
                        <i class="bi bi-house-door"></i>
                        <span>Manage House</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('aprove') }}">
                        <i class="bi bi-check-circle"></i>
                        <span>Approve Rents</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users') }}">
                        <i class="bi bi-people"></i>
                        <span>Manage User</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('approve-user') }}">
                        <i class="bi bi-person-exclamation"></i>
                        <span>Verify User</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('feedback') }}">
                        <i class="bi bi-chat-dots"></i>
                        <span>Feedback</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profit') }}">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Profit</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link activee" href="{{ route('agreement') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Agreements & Payments</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-content" id="mainContent">
            <h1>Manage Agreements & Payments</h1>
            <p>Oversee and manage all rental agreements and their associated payments.</p>

            <div class="container py-4">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="agreements-tab" data-bs-toggle="tab"
                            data-bs-target="#agreements" type="button" role="tab" aria-controls="agreements"
                            aria-selected="true">Agreements</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments"
                            type="button" role="tab" aria-controls="payments"
                            aria-selected="false">Payments</button>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content" id="myTabContent">
                    <!-- Agreements Tab Pane -->
                    <div class="tab-pane fade show active" id="agreements" role="tabpanel"
                        aria-labelledby="agreements-tab">
                        <div class="py-3">
                            <div class="mb-3">
                                <h3 style="color:rgb(50, 149, 235);">Search For Agreements</h3>
                                <input type="text" id="agreementSearchInput" class="form-control"
                                    placeholder="Search agreements by tenant, landlord, house, status...">
                            </div>

                            <div class="card custom-table">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="agreementTable"
                                        style="font-size: 12px;">
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
                    </div>

                    <!-- Payments Tab Pane -->
                    <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                        <div class="py-3">
                            <div class="mb-3">
                                <h3 style="color:rgb(50, 149, 235);">Search For Payments</h3>
                                <input type="text" id="paymentSearchInput" class="form-control"
                                    placeholder="Search payments by tenant, house, amount, status...">
                            </div>

                            <div class="card custom-table">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="paymentTable" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>Payment ID</th>
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
                                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                                    <td>
                                                        <span class="badge 
                                                            @if($payment->status == 'paid' || $payment->status == 'succeeded') bg-success 
                                                            @elseif($payment->status == 'pending') bg-warning text-dark
                                                            @elseif($payment->status == 'failed') bg-danger
                                                            @else bg-secondary @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                                                    <td>{{ Str::limit($payment->notes, 50) ?: 'N/A' }}</td>
                                                    <td class="action-buttons">
                                                        <a href="#" class="btn btn-sm btn-info">View</a>
                                                        {{-- Add other actions if needed --}}
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
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const activeLinks = document.querySelectorAll('.sidebar .nav-link.activee'); // Get all active links

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            activeLinks.forEach(activeLink => { // Iterate over all active links
                if (activeLink) {
                    // This class 'collapsed-active' might not be needed if styling is handled by .sidebar.collapsed .nav-link.activee
                    // activeLink.classList.toggle('collapsed-active'); 
                }
            });
        });


        // Search functionality for agreements table
        document.getElementById('agreementSearchInput').addEventListener('keyup', function() {
            let input = this.value.toLowerCase();
            let rows = document.querySelectorAll('#agreementTable tbody tr');

            rows.forEach(function(row) {
                let textContent = row.textContent || row.innerText;
                row.style.display = textContent.toLowerCase().includes(input) ? '' : 'none';
            });
        });

        // Search functionality for payments table
        document.getElementById('paymentSearchInput').addEventListener('keyup', function() {
            let input = this.value.toLowerCase();
            let rows = document.querySelectorAll('#paymentTable tbody tr');

            rows.forEach(function(row) {
                let textContent = row.textContent || row.innerText;
                row.style.display = textContent.toLowerCase().includes(input) ? '' : 'none';
            });
        });

        // Bootstrap Tab Activation
        var triggerTabList = [].slice.call(document.querySelectorAll('#myTab button'))
        triggerTabList.forEach(function (triggerEl) {
          var tabTrigger = new bootstrap.Tab(triggerEl)

          triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
          })
        })

    </script>
    {{-- Ensure Bootstrap JS is included, typically at the end of the body or in a global layout --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
