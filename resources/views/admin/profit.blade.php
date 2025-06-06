<x-adminLayout>
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <div class="profit-summary">
            <h2 class="mb-0"><i class="bi bi-graph-up me-2"></i>Profit Analytics Dashboard</h2>
            <p class="mb-0 mt-2">Monitor your business performance and revenue distribution</p>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            @php
                $totalProfit = 0;
                if (
                    isset($chartDataFromServer['datasets'][0]['data']) &&
                    is_array($chartDataFromServer['datasets'][0]['data'])
                ) {
                    $totalProfit = array_sum($chartDataFromServer['datasets'][0]['data']);
                }
            @endphp
            <div class="col-md-3 mb-3">
                <div class="profit-card">
                    <div class="profit-amount">${{ number_format($totalProfit) }}</div>
                    <div class="profit-label">Total Profit</div>
                </div>
            </div>
            @if (isset($chartDataFromServer['labels']) && is_array($chartDataFromServer['labels']))
                @foreach ($chartDataFromServer['labels'] as $index => $label)
                    <div class="col-md-3 mb-3">
                        <div class="profit-card">
                            <div class="profit-amount">
                                ${{ number_format($chartDataFromServer['datasets'][0]['data'][$index] ?? 0) }}</div>
                            <div class="profit-label">{{ $label }}</div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Chart Section -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Profit Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 400px;">
                            <canvas id="profitPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Breakdown Details</h5>
                    </div>
                    <div class="card-body">
                        @if (isset($chartDataFromServer['labels']) && is_array($chartDataFromServer['labels']) && $totalProfit > 0)
                            @foreach ($chartDataFromServer['labels'] as $index => $label)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded"
                                    style="background-color: {{ $chartDataFromServer['datasets'][0]['backgroundColor'][$index] ?? '#cccccc' }}20;">
                                    <span class="fw-bold">{{ $label }}</span>
                                    <div class="text-end">
                                        <div class="fw-bold">
                                            ${{ number_format($chartDataFromServer['datasets'][0]['data'][$index] ?? 0) }}
                                        </div>
                                        <small class="text-muted">
                                            {{ number_format((($chartDataFromServer['datasets'][0]['data'][$index] ?? 0) / $totalProfit) * 100, 1) }}%
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">No profit data to display breakdown.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Chart.js CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chartDataFromServer = @json($chartDataFromServer);

                if (chartDataFromServer && chartDataFromServer.labels && chartDataFromServer.datasets) {
                    const ctx = document.getElementById('profitPieChart');
                    if (ctx) {
                        const profitChart = new Chart(ctx.getContext('2d'), {
                            type: 'pie',
                            data: chartDataFromServer,
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
                                                const total = context.dataset.data.reduce((a, b) => a + b,
                                                    0);
                                                const percentage = total ? ((value / total) * 100).toFixed(
                                                    1) : 0;
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

                        ctx.canvas.addEventListener('click', function(event) {
                            const activePoints = profitChart.getElementsAtEventForMode(event, 'nearest', {
                                intersect: true
                            }, true);
                            if (activePoints.length > 0) {
                                const firstPoint = activePoints[0];
                                const label = profitChart.data.labels[firstPoint.index];
                                const value = profitChart.data.datasets[firstPoint.datasetIndex].data[firstPoint
                                    .index];
                                console.log(`Clicked on: ${label} - $${value}`);
                            }
                        });
                    }
                } else {
                    console.warn("Chart data is not available or in the wrong format.");
                    const chartContainer = document.querySelector('.chart-container');
                    if (chartContainer) {
                        chartContainer.innerHTML =
                            '<p class="text-center text-muted mt-5">No chart data available to display.</p>';
                    }
                }
            });

            // The deleteUser function seems out of place here, but kept from original if it has a purpose.
            // function deleteUser(userId) {
            //     if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            //         console.log('Deleting user with ID:', userId);
            //     }
            // }
        </script>
    @endpush

</x-adminLayout>
