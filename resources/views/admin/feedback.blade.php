<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel</title>
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
            width: 80px;
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
            padding: 2rem 2.5rem;
            transition: margin-left 0.4s ease;
            min-height: 100vh;
            background-color: #fff;
            box-shadow: 0 0 15px rgb(0 0 0 / 0.05);
            border-radius: 12px;
            margin-top: 1rem;
            margin-bottom: 2rem;
            width: 100%;
            margin-right: 20px;
        }

        .sidebar.collapsed~.main-content {
            margin-left: 100px;
            width: 100%;
            margin-right: 20px;
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
            width: 180px;
            margin-left: 5px;
        }

        .nav-link.activee.collapsed-active {
            margin-left: 5px;
            width: 60px;
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

        .btn-reject {
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

        .btn-reject:hover {
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
                    <a class="nav-link activee" href="{{ route('feedback') }}">
                        <i class="bi bi-chat-dots"></i>
                        <span>Feedback</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profit') }}">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>profit</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-content" id="mainContent">
            <h1 class="mb-4">User Submissions</h1>

            {{-- User Feedbacks Section --}}
            <div class="mb-5">
                <h2 class="h4 mb-3">User Feedbacks</h2>
                <p class="text-muted mb-3">Review and manage user-submitted feedback.</p>
                <div class="card custom-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($feedbacks as $feedback)
                                    <tr>
                                        <td>{{ $feedback->name }}</td>
                                        <td>{{ $feedback->email }}</td>
                                        <td>{{ $feedback->title }}</td>
                                        <td>{{ Str::limit($feedback->description, 100) }}</td>
                                        <td>{{ $feedback->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ url('delete-feedback', $feedback->id) }}"
                                                onclick="return confirm('Are you sure you want to delete this feedback?')">
                                                <button class="btn-reject">Delete</button>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No feedbacks yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Property Reports Section --}}
            <div>
                <h2 class="h4 mb-3">Property Reports</h2>
                <p class="text-muted mb-3">Review and manage user-submitted property reports.</p>
                <div class="card custom-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Reported By</th>
                                    <th>House Title</th>
                                    <th>Landlord</th>
                                    <th>Reason</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>
                                            {{ $report->reporter->full_name ?? 'N/A' }} <br>
                                            <small class="text-muted">{{ $report->reporter->email ?? '' }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('house.details', $report->house_id) }}" target="_blank">
                                                {{ $report->house->title ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $report->reportedUser->full_name ?? 'N/A' }} <br>
                                            <small class="text-muted">{{ $report->reportedUser->email ?? '' }}</small>
                                        </td>
                                        <td>{{ $report->reason_category }}</td>
                                        <td>{{ Str::limit($report->description, 100) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $report->status === 'pending' ? 'warning' : ($report->status === 'resolved' ? 'success' : ($report->status === 'under_review' ? 'info' : 'secondary')) }} {{ ($report->status === 'pending' || $report->status === 'under_review') ? 'text-dark' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="action-buttons">
                                            <button type="button" class="btn btn-sm btn-info view-report-btn"
                                                    data-bs-toggle="modal" data-bs-target="#reportDetailsModal"
                                                    data-report-id="{{ $report->id }}"
                                                    data-reporter-name="{{ $report->reporter?->full_name ?? 'N/A' }}"
                                                    data-reporter-email="{{ $report->reporter?->email ?? 'N/A' }}"
                                                    data-house-title="{{ $report->house?->title ?? 'N/A' }}"
                                                    data-house-url="{{ ($report->house_id && $report->house) ? route('house.details', $report->house_id) : '#' }}"
                                                    data-reported-landlord-name="{{ $report->reportedUser?->full_name ?? 'N/A' }}"
                                                    data-reported-landlord-email="{{ $report->reportedUser?->email ?? 'N/A' }}"
                                                    data-reason="{{ $report->reason_category ?? 'N/A' }}"
                                                    data-description="{{ $report->description ?? 'N/A' }}"
                                                    data-status="{{ $report->status ?? 'N/A' }}"
                                                    data-date="{{ $report->created_at?->format('Y-m-d H:i') ?? 'N/A' }}">
                                                View
                                            </button>
                                            <form action="{{ route('admin.reports.updateStatus', $report->id) }}" method="POST" class="d-inline-block ms-1">
                                                @csrf
                                                <select name="status" class="form-select form-select-sm d-inline-block" style="width: auto; font-size: 0.8rem; padding: 0.25rem 0.5rem;" onchange="this.form.submit()">
                                                    <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="under_review" {{ $report->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                                    <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                    <option value="dismissed" {{ $report->status === 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                                                </select>
                                                {{-- <button type="submit" class="btn btn-sm btn-outline-secondary ms-1">Update</button> --}}
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">No reports yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const activeLink = document.querySelector('.nav-link.activee');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            if (activeLink) {
                activeLink.classList.toggle('collapsed-active');
            }
        });

        // Report Details Modal Logic
        var reportDetailsModalElement = document.getElementById('reportDetailsModal');
        if (reportDetailsModalElement) {
            reportDetailsModalElement.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                var button = event.relatedTarget;

                // Extract info from data-* attributes using .dataset for cleaner access
                var reportId = button.dataset.reportId;
                var reporterName = button.dataset.reporterName;
                var reporterEmail = button.dataset.reporterEmail;
                var houseTitle = button.dataset.houseTitle;
                var houseUrl = button.dataset.houseUrl;
                var reportedLandlordName = button.dataset.reportedLandlordName;
                var reportedLandlordEmail = button.dataset.reportedLandlordEmail;
                var reason = button.dataset.reason;
                var description = button.dataset.description;
                var status = button.dataset.status;
                var date = button.dataset.date;

                // Update the modal's content, providing fallbacks if data is undefined/null
                var modal = this;
                modal.querySelector('#modalReportId').textContent = reportId || 'N/A';
                modal.querySelector('#modalReporterName').textContent = reporterName || 'N/A';
                modal.querySelector('#modalReporterEmail').textContent = reporterEmail || 'N/A';
                
                const houseLink = modal.querySelector('#modalHouseTitle');
                houseLink.textContent = houseTitle || 'N/A';
                houseLink.href = houseUrl || '#';

                modal.querySelector('#modalReportedLandlordName').textContent = reportedLandlordName || 'N/A';
                modal.querySelector('#modalReportedLandlordEmail').textContent = reportedLandlordEmail || 'N/A';
                modal.querySelector('#modalReason').textContent = reason || 'N/A';
                modal.querySelector('#modalDescription').textContent = description || 'N/A';
                modal.querySelector('#modalStatus').textContent = status ? status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'N/A';
                modal.querySelector('#modalDate').textContent = date || 'N/A';
            });
        }

        function removeFeedback(feedbackId) {
            if (confirm('Are you sure you want to remove this feedback? This action cannot be undone.')) {
                // Logic for removing feedback (can be AJAX)
                window.location.href = `/delete-feedback/${feedbackId}`; // Simple redirect for now
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Report Details Modal -->
    <div class="modal fade" id="reportDetailsModal" tabindex="-1" aria-labelledby="reportDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportDetailsModalLabel">Report Details - ID: <span id="modalReportId"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Reported By:</strong> <span id="modalReporterName"></span> (<small id="modalReporterEmail"></small>)</p>
                            <p><strong>Reported Landlord:</strong> <span id="modalReportedLandlordName"></span> (<small id="modalReportedLandlordEmail"></small>)</p>
                            <p><strong>House:</strong> <a href="#" id="modalHouseTitle" target="_blank"></a></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date Reported:</strong> <span id="modalDate"></span></p>
                            <p><strong>Status:</strong> <span id="modalStatus" class="fw-bold"></span></p>
                            <p><strong>Reason:</strong> <span id="modalReason"></span></p>
                        </div>
                    </div>
                    <hr>
                    <p><strong>Full Description:</strong></p>
                    <p id="modalDescription" style="white-space: pre-wrap;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
