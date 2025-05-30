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

        .btn-accept {
            background-color: #218838;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-accept:hover {
            background-color: #218838;
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
                    <a class="nav-link activee" href="{{ route('aprove') }}">
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
                        <span>profit</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('agreement') }}">
                      <i class="bi bi-file-earmark-text"></i>
                        <span>Agreements & Payments</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-content" id="mainContent">
            <h1>Approve Rents</h1>
            <p>This is your main content section.</p>

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
                                                <td>{{ $house->description }}</td>
                                                <td>
                                                    @foreach ($images as $image)
                                                        @if ($image->house_id == $house->id)
                                                            <img src="{{ $image->image_url }}" width="80px"
                                                                height="80px" alt="">
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="{{ url('approve-house/' . $house->id) }}"
                                                            onclick="return confirm('Are you sure you want to approve this house rental?')"><button
                                                                class="btn-accept">Accept</button></a>
                                                        <a href="{{ url('delete-aprove', $house->id) }}"
                                                            onclick="return confirm('Are you sure you want to reject this house rental?')"><button
                                                                class="btn-reject">Reject</button></a>
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
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const activeLink = document.querySelector('.nav-link.activee');

        toggleBtn.addEventListener('click', () => {
            sidebar
                .classList
                .toggle('collapsed');

            if (activeLink) {
                activeLink
                    .classList
                    .toggle('collapsed-active');
            }
        });

        function approveHouse(houseId) {
            if (confirm('Are you sure you want to approve this house rental?')) {
                console.log('Approving house with ID:', houseId);
                // Example AJAX call:
                // fetch('/approve-house/' + houseId, { method: 'POST' })
                //     .then(response => response.json())
                //     .then(data => console.log(data));
            }
        }

        function rejectHouse(houseId) {
            if (confirm('Are you sure you want to reject this house rental?')) {
                console.log('Rejecting house with ID:', houseId);
                // Example AJAX call:
                // fetch('/reject-house/' + houseId, { method: 'POST' })
                //     .then(response => response.json())
                //     .then(data => console.log(data));
            }
        }
    </script>
</body>

</html>
