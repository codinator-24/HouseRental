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
                $totalProfit = isset($additionalData['totalProfit']) ? $additionalData['totalProfit'] : 0;
                $fivePercentOfTotal = isset($additionalData['fivePercentOfTotal']) ? $additionalData['fivePercentOfTotal'] : 0;
            @endphp
            
            <!-- Total Profit Card -->
            <div class="col-md-3 mb-3">
                <div class="profit-card">
                    <div class="profit-amount">${{ number_format($totalProfit, 1) }}K</div>
                    <div class="profit-label">Total Profit</div>
                </div>
            </div>
            
            <!-- Individual Category Cards -->
            @if (isset($chartDataFromServer['labels']) && is_array($chartDataFromServer['labels']))
                @foreach ($chartDataFromServer['labels'] as $index => $label)
                    <div class="col-md-3 mb-3">
                        <div class="profit-card">
                            <div class="profit-amount">
                                ${{ number_format($chartDataFromServer['datasets'][0]['data'][$index] ?? 0, 1) }}K
                            </div>
                            <div class="profit-label">{{ $label }}</div>
                        </div>
                    </div>
                @endforeach
            @endif
            
            <!-- 5% of Total Profit Card -->
            <div class="col-md-3 mb-3">
                <div class="profit-card" style="border: 2px solid #28a745;">
                    <div class="profit-amount text-success">${{ number_format($fivePercentOfTotal, 2) }}K</div>
                    <div class="profit-label">5% of Total Profit</div>
                </div>
            </div>
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
                                            ${{ number_format($chartDataFromServer['datasets'][0]['data'][$index] ?? 0, 1) }}K
                                        </div>
                                        <small class="text-muted">
                                            {{ number_format((($chartDataFromServer['datasets'][0]['data'][$index] ?? 0) / $totalProfit) * 100, 1) }}%
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Additional info section -->
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded bg-light">
                                <span class="fw-bold text-primary">Total Profit</span>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">${{ number_format($totalProfit, 1) }}K</div>
                                    <small class="text-muted">100%</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background-color: #28a74520;">
                                <span class="fw-bold text-success">5% of Total</span>
                                <div class="text-end">
                                    <div class="fw-bold text-success">${{ number_format($fivePercentOfTotal, 2) }}K</div>
                                    <small class="text-muted">Commission/Fee</small>
                                </div>
                            </div>
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
                                        text: 'Profit Distribution by Property Type',
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
                                                const percentage = total ? ((value / total) * 100).toFixed(1) : 0;
                                                return `${label}: $${value}K (${percentage}%)`;
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
                                const value = profitChart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
                                console.log(`Clicked on: ${label} - $${value}K`);
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
        </script>
    @endpush

</x-adminLayout>