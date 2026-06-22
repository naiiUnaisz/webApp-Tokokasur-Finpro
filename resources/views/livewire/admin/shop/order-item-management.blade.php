<div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 text-white">Detail Item Pesanan</h5>
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama/ID produk..." class="form-control bg-dark text-white border-0">
                </div>
                <div class="col-md-3">
                    <input type="number" wire:model.live.debounce.300ms="orderIdFilter" placeholder="Filter Order ID..." class="form-control bg-dark text-white border-0">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>ID Produk</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td><span class="fw-bold">{{ $item->order->order_number ?? 'N/A' }}</span></td>
                                <td><span class="badge bg-secondary">{{ $item->produk_id }}</span></td>
                                <td>
                                    {{ $item->product_name_snapshot }}
                                    @if($item->product)
                                        <small class="d-block text-success">Tersedia</small>
                                    @else
                                        <small class="d-block text-danger">Dihapus</small>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-info">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @php
                                        $s = $item->order->status ?? 'unknown';
                                        $sc = match($s) {
                                            'pending' => 'warning', 'paid' => 'primary', 'processing' => 'info',
                                            'completed' => 'success', 'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $sc }}">{{ ucfirst($s) }}</span>
                                </td>
                                <td class="text-center">
                                    <button wire:click="deleteItem({{ $item->id }})"
                                            wire:confirm="Yakin hapus item ini? (hanya jika order Pending/Cancelled)"
                                            class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada item ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
