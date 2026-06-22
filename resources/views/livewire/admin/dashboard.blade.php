<div>
    <div class="container-fluid pt-4 px-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white">Dashboard</h5>
            <small class="text-muted">{{ date('d F Y') }}</small>
        </div>
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-users fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Pelanggan</p>
                        <h6 class="mb-0">{{ $totalCustomers }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-box fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Produk</p>
                        <h6 class="mb-0">{{ $totalProducts }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-shopping-cart fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Pesanan</p>
                        <h6 class="mb-0">{{ $totalOrders }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Pendapatan</p>
                        <h6 class="mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-8">
                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Pesanan & Pendapatan per Bulan ({{ date('Y') }})</h6>
                    </div>
                    <canvas id="sales-chart"></canvas>
                </div>
            </div>
            <div class="col-sm-12 col-xl-4">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-hourglass-half fa-3x text-warning"></i>
                    <div class="ms-3">
                        <p class="mb-2">Pesanan Pending</p>
                        <h6 class="mb-0">{{ $pendingOrders }}</h6>
                    </div>
                </div>
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4 mt-4">
                    <i class="fa fa-star fa-3x text-info"></i>
                    <div class="ms-3">
                        <p class="mb-2">Review Menunggu</p>
                        <h6 class="mb-0">{{ $pendingReviews }}</h6>
                    </div>
                </div>
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4 mt-4">
                    <i class="fa fa-users fa-3x text-success"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total User</p>
                        <h6 class="mb-0">{{ $totalUsers }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Pesanan Terbaru</h6>
                <a href="{{ route('admin.orders') }}">Show All</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-white">
                            <th scope="col">Order #</th>
                            <th scope="col">Pelanggan</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($order->status) {
                                            'pending' => 'bg-warning',
                                            'processing' => 'bg-info',
                                            'shipped' => 'bg-primary',
                                            'completed' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.orders') }}">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @script
    <script>
        document.addEventListener('livewire:navigated', () => {
            const monthNames = [
                'Jan','Feb','Mar','Apr','Mei','Jun',
                'Jul','Agu','Sep','Okt','Nov','Des'
            ];

            const orderData = @json($ordersPerMonth);
            const incomeData = @json($incomePerMonth);

            let orderSeries = Array(12).fill(0);
            let incomeSeries = Array(12).fill(0);

            orderData.forEach(item => {
                orderSeries[item.month - 1] = item.total;
            });

            incomeData.forEach(item => {
                incomeSeries[item.month - 1] = item.total;
            });

            const ctx = document.getElementById('sales-chart');
            if (!ctx) return;

            const existing = Chart.getChart(ctx);
            if (existing) existing.destroy();

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthNames,
                    datasets: [
                        {
                            label: 'Pesanan',
                            data: orderSeries,
                            backgroundColor: 'rgba(235, 22, 22, .5)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Pendapatan (Rp)',
                            data: incomeSeries,
                            backgroundColor: 'rgba(235, 22, 22, .7)',
                            fill: false,
                            tension: 0.4,
                            yAxisID: 'y1',
                            borderColor: '#EB1616',
                            pointBackgroundColor: '#EB1616'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#000000' }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: { display: false },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000).toFixed(0) + 'jt';
                                    if (value >= 1000) return (value / 1000).toFixed(0) + 'rb';
                                    return value;
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: { color: '#6C7293' }
                        }
                    }
                }
            });
        });
    </script>
    @endscript
</div>
