<x-adminLayout>
    <div class="main-content" id="mainContent">
        <h1>Approve Rents</h1>
        <p>Review and approve or reject new house rental submissions.</p>

        <div class="">
            <div class="container py-5">
                <div class="card custom-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th scope="col">House type</th>
                                    <th scope="col">Address 1</th>
                                    <th scope="col">Address 2</th>
                                    <th scope="col">City</th>
                                    <th scope="col">No. Rooms</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Rent price</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Photo</th>
                                    <th scope="col">Approve?</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($houses as $house)
                                    @if ($house->status == 'disagree')
                                        <tr>
                                            <td>{{ $house->title }}</td>
                                            <td>{{ $house->first_address }}</td>
                                            <td>{{ $house->second_address }}</td>
                                            <td>{{ $house->city }}</td>
                                            <td>
                                                @php $i = 1; @endphp
                                                @foreach ($floors as $floor)
                                                    @if ($floor->house_id == $house->id)
                                                        <p>floor{{ $i }}: {{ $floor->num_room }}</p>
                                                        @php $i++; @endphp
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $house->square_footage }}m&sup2;</td>
                                            <td>${{ $house->rent_amount }}</td>
                                            <td>{{ Str::limit($house->description, 50) }}</td>
                                            <td>
                                                @foreach ($images as $image)
                                                    @if ($image->house_id == $house->id)
                                                        <img src="{{ $image->image_url }}" width="80px" height="80px"
                                                            alt="House image"
                                                            style="object-fit: cover; border-radius: 4px;">
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ url('approve-house/' . $house->id) }}"
                                                        onclick="return confirm('Are you sure you want to approve this house rental?')"><button
                                                            class="btn btn-sm btn-success">Accept</button></a>
                                                    <a href="{{ url('delete-aprove', $house->id) }}"
                                                        onclick="return confirm('Are you sure you want to reject this house rental?')"><button
                                                            class="btn btn-sm btn-danger">Reject</button></a>
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
            // Specific JavaScript for aprove page, if any.
            // The approveHouse and rejectHouse functions were placeholders in the original file,
            // as the actions are handled by direct links with confirmation.
            // If more complex JS interaction is needed, it would go here.
        </script>
    @endpush

</x-adminLayout>
