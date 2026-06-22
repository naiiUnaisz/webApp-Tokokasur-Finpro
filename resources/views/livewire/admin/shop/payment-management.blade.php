<div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 text-white">Konfirmasi Pembayaran</h5>
            </div>

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari no. order / bank..." class="form-control bg-dark text-white border-0">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="statusFilter" class="form-select bg-dark text-white border-0">
                        <option value="all">Semua Status</option>
                        @foreach ($availableStatuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>Metode</th>
                            <th>Bukti</th>
                            <th>Bank</th>
                            <th>Akun</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $item->order->order_number ?? 'Order Dihapus' }}</span>
                                    <small class="d-block text-muted">Rp {{ number_format($item->order->total_amount ?? 0, 0, ',', '.') }}</small>
                                </td>
                                <td>{{ ucfirst($item->payment_method) }}</td>
                                <td>
                                    @if ($item->proof_image_url)
                                        <a href="{{ asset('storage/' . $item->proof_image_url) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fa fa-image me-1"></i>Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $item->bank_name ?? 'N/A' }}</td>
                                <td>{{ $item->account_name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @php
                                        $sc = match($item->status) {
                                            'pending' => 'warning',
                                            'verified' => 'success',
                                            'rejected' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $sc }}">{{ ucfirst($item->status) }}</span>
                                </td>
                                <td class="text-center">
                                    @if ($item->status === 'pending')
                                        <button wire:click="updateStatus({{ $item->id }}, 'verified')"
                                                wire:confirm="Setujui pembayaran ini?"
                                                class="btn btn-sm btn-success me-1" title="Setujui">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button wire:click="updateStatus({{ $item->id }}, 'rejected')"
                                                wire:confirm="Tolak pembayaran ini?"
                                                class="btn btn-sm btn-danger" title="Tolak">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada pembayaran ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
