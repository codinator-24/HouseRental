<x-adminLayout>
    <div class="main-content" id="mainContent">
        <h1>Manage User</h1>
        <p>Manage your verified users.</p>

        <div class="">
            <div class="container py-5">
                <div class="mb-3">
                    <h3 style="color:rgb(50, 149, 235);">Search For Users</h3>
                    <input type="text" id="userSearchInput" class="form-control" placeholder="Search users...">
                </div>

                
                <div class="card custom-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="userTable" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Full name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Address</th>
                                    <th>Role</th>
                                    <th>Deactivate User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $user)
                                    @if ($user->status == 'Verified')
                                        <tr>
                                            <td data-label="Full name">
                                                <p>{{ $user->full_name }}</p>
                                            </td>
                                            <td data-label="Username">
                                                <p>{{ $user->user_name }}</p>
                                            </td>
                                            <td data-label="Email">
                                                {{ $user->email }}
                                            </td>
                                            <td data-label="Phone number">
                                                {{ $user->first_phoneNumber }}
                                            </td>
                                            <td data-label="Address">
                                                {{ $user->address }}
                                            </td>
                                            <td data-label="Role">
                                                {{ $user->role }}
                                            </td>
                                            <td data-label="Actions">
                                                <!-- Deactivate Button -->
<button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deactivateModal-{{ $user->id }}">
    Deactivate
</button>

<!-- Modal -->
<div class="modal fade" id="deactivateModal-{{ $user->id }}" tabindex="-1" aria-labelledby="deactivateModalLabel-{{ $user->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deactivateModalLabel-{{ $user->id }}">Confirm Deactivation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to deactivate <strong>{{ $user->full_name }}</strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

        <form action="{{ url('deactivate-user', $user->id) }}" method="POST">
            @csrf
            @method('GET') <!-- or DELETE, depending on your route setup -->
            <a href="{{ url('deactivate-user', $user->id) }}">
                                                    <button class="btn btn-sm btn-danger">Deactivate</button>
                                                </a>
        </form>
      </div>
    </div>
  </div>
</div>

                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('userSearchInput');
                const userTable = document.getElementById('userTable');

                if (searchInput && userTable) {
                    const rows = userTable.querySelectorAll('tbody tr');

                    searchInput.addEventListener('keyup', function() {
                        const filter = searchInput.value.toLowerCase();
                        let hasVisibleRows = false;

                        rows.forEach(function(row) {
                            if (row.cells.length === 1 && row.cells[0].colSpan >
                                1) { // "No results" row
                                return;
                            }
                            const text = row.innerText.toLowerCase();
                            if (text.includes(filter)) {
                                row.style.display = '';
                                hasVisibleRows = true;
                            } else {
                                row.style.display = 'none';
                            }
                        });
                        // Optional: Handle "no results" message display
                    });
                }
            });

            // The deleteUser function was a placeholder in the original file.
            // The deactivation is handled by a direct link with confirmation.
            // If more complex JS is needed for deletion/deactivation, it would go here.
            /*
            function deleteUser(userId) {
                if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    console.log('Deleting user with ID:', userId);
                    // AJAX call example
                    // fetch('/delete-user/' + userId, {
                    //     method: 'DELETE',
                    //     headers: {
                    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    //         'Content-Type': 'application/json'
                    //     }
                    // })
                    // .then(response => response.json())
                    // .then(data => {
                    //     if(data.success) {
                    //         location.reload();
                    //     }
                    // })
                    // .catch(error => console.error('Error:', error));
                }
            }
            */
        </script>
    @endpush

</x-adminLayout>
