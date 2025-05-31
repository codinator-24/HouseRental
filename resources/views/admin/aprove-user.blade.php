<x-adminLayout>
    <div class="main-content" id="mainContent">
        <h1>User Management</h1>
        <p>Manage your users.</p>

        <div class="">
            <div class="container py-5">
                <div class="card custom-table">

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Full name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Address</th>
                                    <th>Role</th>
                                    <th>ID Card</th>
                                    <th>Approve User ?</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $user)
                                    @if ($user->status == 'Not Verified')
                                        <tr>
                                            <td data-label="ناو">
                                                <p>{{ $user->full_name }}</p>
                                            </td>
                                            <td data-label="ناو">
                                                <p>{{ $user->user_name }}</p>
                                            </td>
                                            <td data-label="ئیمەیل">
                                                <p>{{ $user->email }}</p>
                                            </td>
                                            <td data-label="مۆبایل">
                                                <p>{{ $user->first_phoneNumber }}</p>
                                            </td>
                                            <td data-label="ناونیشان">
                                                <p>{{ $user->address }}</p>
                                            </td>
                                            <td data-label="جۆری بەکارهێنەر">
                                                <p>{{ $user->role }}</p>
                                            </td>
                                            <td data-label="جۆری بەکارهێنەر">
                                                @if ($user->IdCard)
                                                    @php
                                                        $idCardPath = $user->IdCard;
                                                        $idCardUrl = Storage::url($idCardPath); // Use Storage::url()
                                                        $idCardExtension = strtolower(
                                                            pathinfo($idCardPath, PATHINFO_EXTENSION),
                                                        );
                                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                                    @endphp
                                                    @if (in_array($idCardExtension, $imageExtensions))
                                                        <img src="{{ $idCardUrl }}"
                                                            alt="ID Card for {{ $user->user_name }}" width="150px"
                                                            height="80px"
                                                            style="object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                                                    @elseif ($idCardExtension === 'pdf')
                                                        <a href="{{ $idCardUrl }}" target="_blank"
                                                            class="btn btn-sm btn-outline-primary">View ID Card
                                                            (PDF)</a>
                                                    @else
                                                        <a href="{{ $idCardUrl }}" target="_blank"
                                                            class="btn btn-sm btn-outline-secondary">Download ID
                                                            Card</a>
                                                    @endif
                                                @else
                                                    <p class="text-muted">No ID Card uploaded.</p>
                                                @endif
                                            </td>
                                            <td data-label="گۆرینی ڕؤڵ یان سڕینەوە">
                                                <a href="{{ url('approve-user/' . $user->id) }}"
                                                    onclick="return confirm('Are you sure you want to approve this user?')"><button
                                                        class="btn btn-sm btn-success">Accept</button></a>
                                                <a href="{{ url('delete-user', $user->id) }}"
                                                    onclick="return confirm('Are you sure you want to reject this user registration?')"><button
                                                        class="btn btn-sm btn-danger">Reject</button></a>
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
            // Specific JavaScript for aprove-user page, if any, would go here.
            // For example, if the deleteUser function was uniquely for this page:
            /*
            function deleteUser(userId) {
                if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    // Add your delete logic here
                    console.log('Deleting user with ID:', userId);
                    // Example AJAX call
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
