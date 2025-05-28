<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Profit Analytics</title>
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
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
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
    }

    .chart-container {
        position: relative;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
    }

    .profit-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .profit-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .profit-card:hover {
        transform: translateY(-5px);
    }

    .profit-amount {
        font-size: 2rem;
        font-weight: bold;
        color: #2c3e50;
    }

    .profit-label {
        color: #7f8c8d;
        font-weight: 500;
        margin-top: 0.5rem;
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
                    <a class="nav-link" href="{{ route('aprove') }}">
                        <i class="bi bi-check-circle"></i>
                        <span>Approve Rents</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users') }}">
                        <i class="bi bi-people"></i>
                        <span>Manage User </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('approve-user') }}">
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
                    <a class="nav-link activee" href="#">
                        <i class="bi bi-graph-up"></i>
                        <span>Profit Analytics</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="main-content" id="mainContent">
            <!-- Header -->
            <div class="profit-summary">
                <h2 class="mb-0"><i class="bi bi-graph-up me-2"></i>Profit Analytics Dashboard</h2>
                <p class="mb-0 mt-2">Monitor your business performance and revenue distribution</p>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                @php
                    $totalProfit = array_sum($chartDataFromServer['datasets'][0]['data']);
                @endphp
                <div class="col-md-3">
                    <div class="profit-card">
                        <div class="profit-amount">${{ number_format($totalProfit) }}</div>
                        <div class="profit-label">Total Profit</div>
                    </div>
                </div>
                @foreach($chartDataFromServer['labels'] as $index => $label)
                <div class="col-md-3">
                    <div class="profit-card">
                        <div class="profit-amount">${{ number_format($chartDataFromServer['datasets'][0]['data'][$index]) }}</div>
                        <div class="profit-label">{{ $label }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Chart Section -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Profit Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="profitPieChart" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Breakdown Details</h5>
                        </div>
                        <div class="card-body">
                            @foreach($chartDataFromServer['labels'] as $index => $label)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" 
                                 style="background-color: {{ $chartDataFromServer['datasets'][0]['backgroundColor'][$index] }}20;">
                                <span class="fw-bold">{{ $label }}</span>
                                <div class="text-end">
                                    <div class="fw-bold">${{ number_format($chartDataFromServer['datasets'][0]['data'][$index]) }}</div>
                                    <small class="text-muted">
                                        {{ number_format(($chartDataFromServer['datasets'][0]['data'][$index] / $totalProfit) * 100, 1) }}%
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Chart.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
    const toggleBtn = document.getElementById('toggleBtn');
    const sidebar = document.getElementById('sidebar');
    const activeLink = document.querySelector('.nav-link.activee');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        if (activeLink) {
            activeLink.classList.toggle('collapsed-active');
        }
    });

    // Chart.js Implementation
    document.addEventListener('DOMContentLoaded', function() {
        // Get chart data from Laravel (passed from controller)
        const chartData = @json($chartDataFromServer);
        
        // Get the canvas context
        const ctx = document.getElementById('profitPieChart').getContext('2d');
        
        // Create the pie chart
        const profitChart = new Chart(ctx, {
            type: 'pie',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Profit Distribution by Category',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: 20
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: $${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 1000
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Optional: Add click event to chart segments
        ctx.canvas.addEventListener('click', function(event) {
            const activePoints = profitChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
            
            if (activePoints.length > 0) {
                const firstPoint = activePoints[0];
                const label = profitChart.data.labels[firstPoint.index];
                const value = profitChart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
                
                console.log(`Clicked on: ${label} - $${value}`);
                // You can add more functionality here, like showing detailed breakdown
            }
        });
    });

    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            console.log('Deleting user with ID:', userId);
            // Add your delete logic here
        }
    }
</script>

</body>

</html>