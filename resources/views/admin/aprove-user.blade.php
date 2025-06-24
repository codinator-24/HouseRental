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
                                                <!-- Accept Button -->
<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#acceptModal-{{ $user->id }}">
    Accept
</button>

<!-- Accept Modal -->
<div class="modal fade" id="acceptModal-{{ $user->id }}" tabindex="-1" aria-labelledby="acceptModalLabel-{{ $user->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="acceptModalLabel-{{ $user->id }}">Confirm Approval</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to approve <strong>{{ $user->full_name }}</strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form action="{{ route('admin.users.approve', $user->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Accept</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Reject Button -->
<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $user->id }}">
    Reject
</button>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal-{{ $user->id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $user->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel-{{ $user->id }}">Confirm Rejection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to reject the registration of <strong>{{ $user->full_name }}</strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Reject</button>
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
