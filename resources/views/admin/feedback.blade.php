<x-adminLayout>
    <div class="main-content" id="mainContent">
        <h1 class="mb-4">User Submissions</h1>

        <!-- Custom Tab Navigation -->
        <ul class="nav nav-tabs custom-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="feedbacks-tab" data-bs-toggle="tab" data-bs-target="#feedbacks"
                    type="button" role="tab" aria-controls="feedbacks" aria-selected="true">
                    <i class="bi bi-chat-dots"></i>
                    User Feedbacks
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button"
                    role="tab" aria-controls="reports" aria-selected="false">
                    <i class="bi bi-flag"></i>
                    Property Reports
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="user-reports-tab" data-bs-toggle="tab" data-bs-target="#user-reports" type="button"
                    role="tab" aria-controls="user-reports" aria-selected="false">
                    <i class="bi bi-person-x"></i>
                    User Reports
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- User Feedbacks Tab -->
            <div class="tab-pane fade show active" id="feedbacks" role="tabpanel" aria-labelledby="feedbacks-tab">
                <div class="tab-header">
                    <h2 class="h4">User Feedbacks</h2>
                    <p class="text-muted">Review and manage user-submitted feedback.</p>
                </div>

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
                                            <!-- Delete Button -->
<button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFeedbackModal-{{ $feedback->id }}" style="width:60%;">
    Delete
</button>

<!-- Delete Feedback Modal -->
<div class="modal fade" id="deleteFeedbackModal-{{ $feedback->id }}" tabindex="-1" aria-labelledby="deleteFeedbackModalLabel-{{ $feedback->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteFeedbackModalLabel-{{ $feedback->id }}">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this <strong>feedback</strong>?
        <blockquote class="blockquote mt-2 mb-0">
            <p class="mb-0">{{ $feedback->description ?? 'No message available.' }}</p>
        </blockquote>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form action="{{ route('admin.feedback.delete', $feedback->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

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

            <!-- Property Reports Tab -->
            <div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">
                <div class="tab-header">
                    <h2 class="h4">Property Reports</h2>
                    <p class="text-muted">Review and manage user-submitted property reports.</p>
                </div>

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
                                            <a href="{{ $report->house_id && $report->house ? route('house.details', $report->house_id) : '#' }}"
                                                target="_blank">
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
                                            <span
                                                class="badge bg-{{ $report->status === 'pending' ? 'warning' : ($report->status === 'resolved' ? 'success' : ($report->status === 'under_review' ? 'info' : ($report->status === 'dismissed' ? 'secondary' : 'light'))) }} {{ $report->status === 'pending' || $report->status === 'under_review' ? 'text-dark' : '' }}">
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
                                                data-house-url="{{ $report->house_id && $report->house ? route('house.details', $report->house_id) : '#' }}"
                                                data-reported-landlord-name="{{ $report->reportedUser?->full_name ?? 'N/A' }}"
                                                data-reported-landlord-email="{{ $report->reportedUser?->email ?? 'N/A' }}"
                                                data-reason="{{ $report->reason_category ?? 'N/A' }}"
                                                data-description="{{ $report->description ?? 'N/A' }}"
                                                data-status="{{ $report->status ?? 'N/A' }}"
                                                data-date="{{ $report->created_at?->format('Y-m-d H:i') ?? 'N/A' }}">
                                                View
                                            </button>
                                            <form action="{{ route('admin.reports.updateStatus', $report->id) }}"
                                                method="POST" class="d-inline-block ms-1">
                                                @csrf
                                                <select name="status" class="form-select form-select-sm d-inline-block"
                                                    style="width: auto; font-size: 0.8rem; padding: 0.25rem 0.5rem;"
                                                    onchange="this.form.submit()">
                                                    <option value="pending"
                                                        {{ $report->status === 'pending' ? 'selected' : '' }}>Pending
                                                    </option>
                                                    <option value="under_review"
                                                        {{ $report->status === 'under_review' ? 'selected' : '' }}>
                                                        Under Review</option>
                                                    <option value="resolved"
                                                        {{ $report->status === 'resolved' ? 'selected' : '' }}>Resolved
                                                    </option>
                                                    <option value="dismissed"
                                                        {{ $report->status === 'dismissed' ? 'selected' : '' }}>
                                                        Dismissed</option>
                                                </select>
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

            <!-- User Reports Tab -->
            <div class="tab-pane fade" id="user-reports" role="tabpanel" aria-labelledby="user-reports-tab">
                <div class="tab-header">
                    <h2 class="h4">User Reports</h2>
                    <p class="text-muted">Review and manage user-submitted reports against other users.</p>
                </div>

                <div class="card custom-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Reported By</th>
                                    <th>Reported User</th>
                                    <th>Related House</th>
                                    <th>Reason</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($userReports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>
                                            {{ $report->reporter->full_name ?? 'N/A' }} <br>
                                            <small class="text-muted">{{ $report->reporter->email ?? '' }}</small>
                                        </td>
                                        <td>
                                            {{ $report->reportedUser->full_name ?? 'N/A' }} <br>
                                            <small class="text-muted">{{ $report->reportedUser->email ?? '' }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ $report->house_id && $report->house ? route('house.details', $report->house_id) : '#' }}"
                                                target="_blank">
                                                {{ $report->house->title ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td>{{ $report->reason_category }}</td>
                                        <td>{{ Str::limit($report->description, 100) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $report->status === 'pending' ? 'warning' : ($report->status === 'resolved' ? 'success' : ($report->status === 'under_review' ? 'info' : ($report->status === 'dismissed' ? 'secondary' : 'light'))) }} {{ $report->status === 'pending' || $report->status === 'under_review' ? 'text-dark' : '' }}">
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
                                                data-house-url="{{ $report->house_id && $report->house ? route('house.details', $report->house_id) : '#' }}"
                                                data-reported-landlord-name="{{ $report->reportedUser?->full_name ?? 'N/A' }}"
                                                data-reported-landlord-email="{{ $report->reportedUser?->email ?? 'N/A' }}"
                                                data-reason="{{ $report->reason_category ?? 'N/A' }}"
                                                data-description="{{ $report->description ?? 'N/A' }}"
                                                data-status="{{ $report->status ?? 'N/A' }}"
                                                data-date="{{ $report->created_at?->format('Y-m-d H:i') ?? 'N/A' }}">
                                                View
                                            </button>
                                            <form action="{{ route('admin.reports.updateStatus', $report->id) }}"
                                                method="POST" class="d-inline-block ms-1">
                                                @csrf
                                                <select name="status" class="form-select form-select-sm d-inline-block"
                                                    style="width: auto; font-size: 0.8rem; padding: 0.25rem 0.5rem;"
                                                    onchange="this.form.submit()">
                                                    <option value="pending"
                                                        {{ $report->status === 'pending' ? 'selected' : '' }}>Pending
                                                    </option>
                                                    <option value="under_review"
                                                        {{ $report->status === 'under_review' ? 'selected' : '' }}>
                                                        Under Review</option>
                                                    <option value="resolved"
                                                        {{ $report->status === 'resolved' ? 'selected' : '' }}>Resolved
                                                    </option>
                                                    <option value="dismissed"
                                                        {{ $report->status === 'dismissed' ? 'selected' : '' }}>
                                                        Dismissed</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">No user reports yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Details Modal -->
    <div class="modal fade" id="reportDetailsModal" tabindex="-1" aria-labelledby="reportDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportDetailsModalLabel">Report Details - ID: <span
                            id="modalReportId"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Reported By:</strong> <span id="modalReporterName"></span> (<small
                                    id="modalReporterEmail"></small>)</p>
                            <p><strong>Reported Landlord:</strong> <span id="modalReportedLandlordName"></span> (<small
                                    id="modalReportedLandlordEmail"></small>)</p>
                            <p><strong>House:</strong> <a href="#" id="modalHouseTitle" target="_blank"></a>
                            </p>
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

    @push('scripts')
        <script>
            // Report Details Modal Logic
            var reportDetailsModalElement = document.getElementById('reportDetailsModal');
            if (reportDetailsModalElement) {
                reportDetailsModalElement.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
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
                    modal.querySelector('#modalStatus').textContent = status ? status.replace(/_/g, ' ').replace(
                        /\b\w/g, l => l.toUpperCase()) : 'N/A';
                    modal.querySelector('#modalDate').textContent = date || 'N/A';
                });
            }

            function removeFeedback(
            feedbackId) { // This function was defined but not used in the original HTML structure for the delete button.
                if (confirm('Are you sure you want to remove this feedback? This action cannot be undone.')) {
                    window.location.href = `/delete-feedback/${feedbackId}`;
                }
            }
        </script>
    @endpush


</x-adminLayout>
