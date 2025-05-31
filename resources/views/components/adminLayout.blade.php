<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <link rel="shortcut icon" type="image" href="{{ asset('images/logo.png') }}">
    {{-- <link rel="stylesheet" href="{{ asset('style/style.css') }}"> --}} {{-- Assuming you have a global style.css in public/style --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
</head>

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
        /* Adjusted width */
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
        width: 90px;
        /* Adjusted collapsed width */
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

    main.main-content {
        /* Adjusted selector to target main tag */
        margin-left: 250px;
        /* Adjusted margin */
        padding: 2rem 2.5rem;
        transition: margin-left 0.4s ease;
        min-height: 100vh;
        background-color: #fff;
        box-shadow: 0 0 15px rgb(0 0 0 / 0.05);
        border-radius: 12px;
        margin-top: 1rem;
        margin-bottom: 2rem;
        width: calc(100% - 270px);
        /* Adjusted width */
        margin-right: 20px;
    }

    .sidebar.collapsed~main.main-content {
        /* Adjusted selector to target main tag */
        margin-left: 100px;
        /* Adjusted margin */
        width: calc(100% - 120px);
        /* Adjusted width */
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
        width: calc(100% - 10px);
        /* Adjusted width for active link */
        margin-left: 5px;
        margin-right: 5px;
    }

    .sidebar.collapsed .nav-link.activee {
        width: 60px;
        /* Fixed width for collapsed active link */
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

    .btn-delete {
        /* This class might be unused in this specific view, but kept for consistency if used elsewhere */
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

    .btn-delete:hover {
        background-color: #c82333;
    }
</style>

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
                    <a class="nav-link {{ request()->routeIs('AdminDashboard') ? 'activee' : '' }}"
                        href="{{ route('AdminDashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users') ? 'activee' : '' }}" href="{{ route('users') }}">
                        <i class="bi bi-people"></i>
                        <span>Manage User</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('approve-user') ? 'activee' : '' }}"
                        href="{{ route('approve-user') }}">
                        <i class="bi bi-person-exclamation"></i>
                        <span>Verify User</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('houses') ? 'activee' : '' }}"
                        href="{{ route('houses') }}">
                        <i class="bi bi-house-door"></i>
                        <span>Manage House</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('aprove') ? 'activee' : '' }}"
                        href="{{ route('aprove') }}">
                        <i class="bi bi-check-circle"></i>
                        <span>Approve Rents</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('agreement') ? 'activee' : '' }}"
                        href="{{ route('agreement') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Agreements</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('payment') ? 'activee' : '' }}"
                        href="{{ route('payment') }}">
                        <i class="bi bi-credit-card"></i>
                        <span>Payments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profit') ? 'activee' : '' }}"
                        href="{{ route('profit') }}">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Profit</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('feedback') ? 'activee' : '' }}"
                        href="{{ route('feedback') }}">
                        <i class="bi bi-chat-dots"></i>
                        <span>Feedback</span>
                    </a>
                </li>
                 </li>
                {{-- 1. New Communications Nav Item --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.communications') ? 'activee' : '' }}"
                        href="#"> {{-- Replace # with actual route e.g., {{ route('admin.communications') }} --}}
                        <i class="bi bi-chat-left-text"></i> {{-- Official chat icon --}}
                        <span>Communications</span>
                    </a>
                </li>
            </ul>

             {{-- 2. Settings button at the bottom of the sidebar --}}
            <ul class="nav nav-pills flex-column w-100 mt-auto" style="padding-bottom: 1rem;">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings') ? 'activee' : '' }}"
                        href="#"> {{-- Replace # with actual route e.g., {{ route('admin.settings') }} --}}
                        <i class="bi bi-gear"></i> {{-- Settings icon --}}
                       
                    </a>
                </li>
            </ul>
        </nav>

        <main class="main-content" id="mainContent"> {{-- Apply class and ID here --}}
            {{ $slot }}
        </main>
    </div>
    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        // No need to query for activeLinks here as it's handled by CSS and route checks

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            // The active state styling should be handled by CSS rules like:
            // .sidebar.collapsed .nav-link.activee
        });

        // Agreements table filtering script has been moved to agreements.blade.php
    </script>

    {{-- Bootstrap JS bundle is generally good to keep if other Bootstrap components are used in your layout or might be added later. --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    @stack('scripts') {{-- Stack for page-specific scripts --}}
</body>

</html>
