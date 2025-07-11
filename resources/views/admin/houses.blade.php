<x-adminLayout>
    <div class="main-content" id="mainContent">
        <h1>Manage House</h1>
        <p>Oversee all active house listings.</p>

        <div class="">
            <div class="container py-5">
                <div class="search" style="margin-bottom:3%;">
                    <h3 style="color:rgb(50, 149, 235);">Search For House</h3>
                    <input type="text" id="houseSearchInput" class="form-control" placeholder="Search house...">
                </div>

                <div class="card custom-table">
                    <div class="table-responsive">
                        <table id="houseTable" class="table table-hover mb-0" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th scope="col">House type</th>
                                    <th scope="col">Address 1</th>
                                    <th scope="col">Address <br>2</th>
                                    <th scope="col">City</th>
                                    <th scope="col">No. Rooms</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Rent price</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Photo</th>
                                    <th scope="col">Deactivate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($houses as $house)
                                    @if ($house->status == 'available')
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
                                                    <!-- Deactivate House Button -->
<button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deactivateHouseModal-{{ $house->id }}">
    Deactivate
</button>

<!-- Deactivate House Modal -->
<div class="modal fade" id="deactivateHouseModal-{{ $house->id }}" tabindex="-1" aria-labelledby="deactivateHouseModalLabel-{{ $house->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deactivateHouseModalLabel-{{ $house->id }}">Confirm Deactivation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to deactivate the listing for <strong>{{ $house->title ?? 'this house' }}</strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form action="{{ route('admin.houses.deactivate', $house->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger">Deactivate</button>
        </form>
      </div>
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
                const input = document.getElementById('houseSearchInput');
                const table = document.getElementById('houseTable');
                if (input && table) {
                    const rows = table.querySelectorAll('tbody tr');

                    input.addEventListener('keyup', function() {
                        const filter = input.value.toLowerCase();
                        let hasVisibleRows = false;

                        rows.forEach(function(row) {
                            if (row.cells.length === 1 && row.cells[0].colSpan >
                                1) { // Assuming "no results" row spans multiple columns
                                return;
                            }
                            const rowText = row.textContent.toLowerCase();
                            if (rowText.includes(filter)) {
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
        </script>
    @endpush

</x-adminLayout>
