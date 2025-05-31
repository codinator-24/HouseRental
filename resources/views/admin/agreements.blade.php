<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Agreements</title>
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
            width: 225px;
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

        .btn-delete { /* This class might be unused in this specific view, but kept for consistency if used elsewhere */
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
                        <span>Agreements</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('payment') }}">
                        <i class="bi bi-credit-card"></i>
                        <span>Payments</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-content" id="mainContent">
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
                    {{-- End of Agreements Table Section --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const activeLinks = document.querySelectorAll('.sidebar .nav-link.activee'); 

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            activeLinks.forEach(activeLink => { 
                if (activeLink) {
                    // The 'collapsed-active' class might not be strictly necessary if 
                    // .sidebar.collapsed .nav-link.activee CSS handles the collapsed active state.
                    // activeLink.classList.toggle('collapsed-active'); 
                }
            });
        });


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