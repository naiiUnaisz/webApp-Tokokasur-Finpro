<div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 text-white">Manajemen Pesanan</h5>
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari no. order / pelanggan..." class="form-control bg-dark text-white border-0">
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
                            <th>Pelanggan</th>
                            <th>No. Order</th>
                            <th class="text-end">Total</th>
                            <th>Status</th>
                            <th>Kurir</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $item->user->name ?? 'User Dihapus' }}</span>
                                    <small class="d-block text-muted">#{{ $item->user_id }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $item->order_number }}</span>
                                    <small class="d-block text-muted">{{ $item->created_at->format('d M y H:i') }}</small>
                                </td>
                                <td class="text-end fw-bold text-info">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $sc = match($item->status) {
                                            'pending' => 'warning',
                                            'paid' => 'primary',
                                            'processing' => 'info',
                                            'shipped' => 'secondary',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $sc }}">{{ $availableStatuses[$item->status] ?? ucfirst($item->status) }}</span>
                                </td>
                                <td>
                                    <small class="d-block">{{ $item->courier_name ?? 'Belum diatur' }}</small>
                                    <small class="text-muted">{{ $item->tracking_number ?? '' }}</small>
                                </td>
                                <td class="text-center">
                                    <button wire:click="openDetailModal({{ $item->id }})" class="btn btn-sm btn-info me-1" title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button wire:click="openEditModal({{ $item->id }})" class="btn btn-sm btn-primary me-1" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button wire:click="deleteOrder({{ $item->id }})"
                                            wire:confirm="Yakin hapus order ini? (hanya untuk Pending/Cancelled)"
                                            class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Tidak ada pesanan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    @if ($showEditModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.6);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header border-bottom border-dark">
                    <h5 class="modal-title text-white">Update Pesanan</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showEditModal', false)"></button>
                </div>
                <form wire:submit.prevent="updateOrder">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select wire:model="currentStatus" class="form-select bg-dark text-white border-0">
                                @foreach ($availableStatuses as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('currentStatus') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Kurir</label>
                            <input type="text" wire:model="courier_name" class="form-control bg-dark text-white border-0">
                            @error('courier_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Pelacakan</label>
                            <input type="text" wire:model="tracking_number" class="form-control bg-dark text-white border-0">
                            @error('tracking_number') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea wire:model="notes" rows="2" class="form-control bg-dark text-white border-0"></textarea>
                            @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-top border-dark">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showEditModal', false)">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-sync-alt me-1"></i>Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal Detail --}}
    @if ($showDetailModal && $detailOrder)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.6);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header border-bottom border-dark">
                    <h5 class="modal-title text-white">Detail {{ $detailOrder->order_number }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showDetailModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold">{{ $detailOrder->created_at->format('d M Y H:i') }}</span>
                        @php
                            $sc = match($detailOrder->status) {
                                'pending' => 'warning', 'paid' => 'primary', 'processing' => 'info',
                                'shipped' => 'secondary', 'completed' => 'success', 'cancelled' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $sc }} fs-6">{{ ucfirst($detailOrder->status) }}</span>
                    </div>

                    <div class="bg-dark rounded p-3 mb-3">
                        <h6 class="text-white">Pelanggan</h6>
                        <p class="mb-1">{{ $detailOrder->user->name ?? '-' }}</p>
                        <h6 class="text-white mt-3">Alamat Kirim</h6>
                        @if ($detailOrder->address)
                            <p class="mb-0">{{ $detailOrder->address->recipient_name }} - {{ $detailOrder->address->phone_number }}</p>
                            <p class="mb-0">{{ $detailOrder->address->address }}, {{ $detailOrder->address->city }}</p>
                            <p>{{ $detailOrder->address->province }} - {{ $detailOrder->address->postal_code }}</p>
                        @else
                            <p class="text-muted">Alamat tidak tersedia</p>
                        @endif
                    </div>

                    <h6 class="text-white">Produk Dibeli</h6>
                    <div class="bg-dark rounded p-3">
                        @foreach ($detailOrder->orderItems as $item)
                            <div class="d-flex justify-content-between border-bottom border-secondary pb-2 mb-2">
                                <div>
                                    <span class="fw-bold">{{ $item->product->name ?? 'Produk dihapus' }}</span>
                                    <small class="d-block text-muted">{{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</small>
                                </div>
                                <span class="fw-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-top border-dark">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showDetailModal', false)">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
