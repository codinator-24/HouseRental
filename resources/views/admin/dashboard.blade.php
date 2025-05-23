<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Admin Panel</title>
        <link rel="shortcut icon" type="image" href="./images/logo.png"/>
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
            crossorigin="anonymous"/>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
            rel="stylesheet"/>
        <link
            href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap"
            rel="stylesheet"/>

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
            table {
                width: 100%;
                table-layout: auto;
                /* dynamic column widths */
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
                        <a class="nav-link activee" href="{{route('AdminDashboard')}}">
                            <i class="bi bi-grid"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('aprove')}}">
                            <i class="bi bi-check-circle"></i>
                            <span>Approve Rents</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('users')}}">
                            <i class="bi bi-people"></i>
                            <span>User Management</span>
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

            <div class="main-content" id="mainContent">
                <h1>Dashboard</h1>
                <p>This is your main content section.</p>

                <div class="container mt-4">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 text-center">

                        <div class="col">
                            <div class="card shadow rounded-4 p-3">
                                <i class="bi bi-people text-secondary"></i>
                                <h5 class="mt-2">Total Users</h5>
                                <h3>{{$users}}</h3>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card shadow rounded-4 p-3">
                                <i class="bi bi-person-check text-success" style="color:#0f7a99;"></i>
                                <h5 class="mt-2">Landlord Users</h5>
                                <h3>{{$landlords}}</h3>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card shadow rounded-4 p-3">
                                <i class="bi bi-person-up text-primary" ></i>
                                <h5 class="mt-2">Tenant Users</h5>
                                <h3>{{$tenants}}</h3>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card shadow rounded-4 p-3">
                                <i class="bi bi-person-exclamation text-danger" ></i>
                                <h5 class="mt-2">Users Need Verify</h5>
                                <h3>{{$verify}}</h3>
                            </div>
                        </div>

                        <!-- <div class="col">
                            <div class="card shadow rounded-4 p-3">
                                <i class="bi bi-cash-stack text-success"></i>
                                <h5 class="mt-2">Total Benefits</h5>
                                <h3>$4,567</h3>
                            </div>
                        </div> -->

                        <div class="col">
                            <div class="card shadow rounded-4 p-3">
                                <i class="bi bi-check-circle text-info"></i>
                                <h5 class="mt-2">Houses Need Approve</h5>
                                <h3>{{$aproves}}</h3>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card shadow rounded-4 p-3">
                                <i class="bi bi-house text-warning"></i>
                                <h5 class="mt-2">Total Houses</h5>
                                <h3>{{$houses}}</h3>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card shadow rounded-4 p-3">
                                <i class="bi bi-chat-dots text-dark"></i>
                                <h5 class="mt-2">Feedback</h5>
                                <h3>342</h3>
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
        </script>
    </body>

</html>