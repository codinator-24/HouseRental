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
<<<<<<< HEAD
                <ul class="nav nav-pills flex-column w-100">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('AdminDashboard')}}">
                            <i class="bi bi-grid"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('houses')}}">
                             <i class="bi bi-house-door"></i>
                            <span>Manage House</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('aprove')}}">
                            <i class="bi bi-check-circle"></i>
                            <span>Approve Rents</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link activee" href="{{route('users')}}">
                            <i class="bi bi-people"></i>
                            <span>Manage User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{route('approve-user')}}">
                            <i class="bi bi-person-exclamation"></i>
                            <span>Verify User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('feedback')}}">
                            <i class="bi bi-chat-dots"></i>
                            <span>Feedback</span>
                        </a>
                    </li>
                </ul>
            </nav>
=======
>>>>>>> 234550a4284944a16d8fbb5653c9072080d83158

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
                                                <a href="{{ url('deactivate-user', $user->id) }}"
                                                    onclick="return confirm('Are you sure you want to deactivate this user?')">
                                                    <button class="btn btn-sm btn-danger">Deactivate</button>
                                                </a>
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
