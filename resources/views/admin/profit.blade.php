<x-adminLayout>
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <div class="profit-summary">
            <h2 class="mb-0"><i class="bi bi-graph-up me-2"></i>Profit Analytics Dashboard</h2>
            <p class="mb-0 mt-2">Monitor your business performance and revenue distribution</p>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4" id="summaryCardsContainer">
            <!-- This section will be dynamically updated by JavaScript -->
        </div>

        <!-- Chart Section -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Profit Distribution</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="dataToggleSwitch">
                            <label class="form-check-label" for="dataToggleSwitch">Show Real Data</label>
                        </div>
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
                    <div class="card-body" id="breakdownDetailsContainer">
                        <!-- This section will be dynamically updated by JavaScript -->
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Houses per City</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 400px;">
                            <canvas id="housesPerCityChart"></canvas>
                        </div>
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
                const realProfitData = @json($chartDataFromServer ?? null);
                const fakeProfitChartData = @json($fakeProfitChartData ?? null);
                const realAdditionalData = @json($additionalData ?? null);
                const fakeAdditionalData = @json($fakeAdditionalData ?? null);

                const profitPieChartCtx = document.getElementById('profitPieChart');
                let profitChart;

                const chartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: { display: true, text: 'Profit Distribution by Property Type', font: { size: 16, weight: 'bold' }, padding: 20 },
                        legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, font: { size: 12 } } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: $${context.parsed.toFixed(1)}K (${percentage}%)`;
                                }
                            }
                        }
                    }
                };

                function createOrUpdateProfitChart(data) {
                    if (profitChart) {
                        profitChart.data = data;
                        profitChart.update();
                    } else if (profitPieChartCtx) {
                        profitChart = new Chart(profitPieChartCtx.getContext('2d'), { type: 'pie', data: data, options: chartOptions });
                    }
                }

                function updateDashboard(chartData, additionalInfo) {
                    // Update Summary Cards
                    const summaryContainer = document.getElementById('summaryCardsContainer');
                    let summaryHtml = `
                        <div class="col-md-3 mb-3">
                            <div class="profit-card">
                                <div class="profit-amount">$${additionalInfo.totalProfit.toFixed(1)}K</div>
                                <div class="profit-label">Total Profit</div>
                            </div>
                        </div>`;
                    if(additionalInfo.cards) {
                        additionalInfo.cards.forEach(card => {
                            summaryHtml += `
                                <div class="col-md-3 mb-3">
                                    <div class="profit-card">
                                        <div class="profit-amount">$${card.value.toFixed(1)}K</div>
                                        <div class="profit-label">${card.label}</div>
                                    </div>
                                </div>`;
                        });
                    }
                    summaryHtml += `
                        <div class="col-md-3 mb-3">
                            <div class="profit-card" style="border: 2px solid #28a745;">
                                <div class="profit-amount text-success">$${additionalInfo.fivePercentOfTotal.toFixed(2)}K</div>
                                <div class="profit-label">5% of Total Profit</div>
                            </div>
                        </div>`;
                    summaryContainer.innerHTML = summaryHtml;

                    // Update Breakdown Details
                    const breakdownContainer = document.getElementById('breakdownDetailsContainer');
                    let breakdownHtml = '';
                    if (additionalInfo.breakdown && additionalInfo.breakdown.length > 0) {
                        additionalInfo.breakdown.forEach(item => {
                            breakdownHtml += `
                                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background-color: rgba(0,0,0,0.05);">
                                    <span class="fw-bold">${item.label}</span>
                                    <div class="text-end">
                                        <div class="fw-bold">$${item.value.toFixed(1)}K</div>
                                        <small class="text-muted">${item.percentage.toFixed(1)}%</small>
                                    </div>
                                </div>`;
                        });
                        breakdownHtml += `<hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded bg-light">
                                <span class="fw-bold text-primary">Total Profit</span>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">$${additionalInfo.totalProfit.toFixed(1)}K</div>
                                    <small class="text-muted">100%</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background-color: #28a74520;">
                                <span class="fw-bold text-success">5% of Total</span>
                                <div class="text-end">
                                    <div class="fw-bold text-success">$${additionalInfo.fivePercentOfTotal.toFixed(2)}K</div>
                                    <small class="text-muted">Commission/Fee</small>
                                </div>
                            </div>`;
                    } else {
                        breakdownHtml = '<p class="text-muted text-center">No profit data to display breakdown.</p>';
                    }
                    breakdownContainer.innerHTML = breakdownHtml;

                    // Update Chart
                    createOrUpdateProfitChart(chartData);
                }
                
                // Initial Load with Fake Data
                updateDashboard(fakeProfitChartData, fakeAdditionalData);

                document.getElementById('dataToggleSwitch').addEventListener('change', function(event) {
                    if (event.target.checked) {
                        updateDashboard(realProfitData, {
                            totalProfit: realAdditionalData.totalProfit,
                            fivePercentOfTotal: realAdditionalData.fivePercentOfTotal,
                            breakdown: realProfitData.labels.map((label, index) => ({
                                label: label,
                                value: realProfitData.datasets[0].data[index],
                                percentage: realAdditionalData.totalProfit > 0 ? (realProfitData.datasets[0].data[index] / realAdditionalData.totalProfit) * 100 : 0
                            })),
                            cards: realProfitData.labels.map((label, index) => ({
                                label: label,
                                value: realProfitData.datasets[0].data[index]
                            }))
                        });
                    } else {
                        updateDashboard(fakeProfitChartData, fakeAdditionalData);
                    }
                });

                // --- Houses per City Bar Chart ---
                const housesChartData = @json($housesPerCityChart ?? null);
                const housesBarChartCtx = document.getElementById('housesPerCityChart');
                if (housesChartData && housesChartData.labels.length > 0 && housesBarChartCtx) {
                    new Chart(housesBarChartCtx.getContext('2d'), {
                        type: 'bar',
                        data: housesChartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                title: { display: true, text: 'Number of Houses per City', font: { size: 16, weight: 'bold' }, padding: 20 },
                                legend: { display: false }
                            },
                            scales: {
                                y: { beginAtZero: true, ticks: { stepSize: 1 } }
                            }
                        }
                    });
                } else if (housesBarChartCtx) {
                    housesBarChartCtx.parentElement.innerHTML = '<p class="text-center text-muted mt-5">No city data available.</p>';
                }
            });
        </script>
    @endpush

</x-adminLayout>
