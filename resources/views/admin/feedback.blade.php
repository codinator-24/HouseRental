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

            .btn-remove {
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

            .btn-remove:hover {
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
                        <a class="nav-link" href="{{route('AdminDashboard')}}">
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
                        <a class="nav-link activee" href="{{route('feedback')}}">
                            <i class="bi bi-chat-dots"></i>
                            <span>Feedback</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="main-content" id="mainContent">
                <h1>Feedbacks</h1>
                <p>Grow with feedback.</p>

                <div class="">
                    <div class="container py-5">
                        <div class="card custom-table">

                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>About</th>
                                            <th>Message</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                Ali Khalid
                                            </td>
                                            <td>
                                                alikhalidahmed1@gmail.com
                                            </td>
                                            <td>
                                                Tenant
                                            </td>
                                            <td>
                                                Best Regards
                                            </td>
                                            <td>
                                                <button class="btn-remove" >Remove</button>
                                            </td>
                                        </tr>
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

            function removeFeedback(feedbackId) {
                if (confirm('Are you sure you want to remove this feedback? This action cannot be undone.')) {
                    // Add your remove feedback logic here
                    console.log('Removing feedback with ID:', feedbackId);
                    // You can make an AJAX call to your Laravel route here Example:
                    // fetch('/remove-feedback/' + feedbackId, {     method: 'DELETE',     headers:
                    // {         'X-CSRF-TOKEN':
                    // document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    // 'Content-Type': 'application/json'     } }) .then(response =>
                    // response.json()) .then(data => {     if(data.success) {          Remove the
                    // row from table or reload page         location.reload();     } })
                    // .catch(error => console.error('Error:', error));
                }
            }
        </script>
    </body>

</html>