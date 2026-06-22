<div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 text-white">Ulasan Produk</h5>
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
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari komentar / ID produk..." class="form-control bg-dark text-white border-0">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="statusFilter" class="form-select bg-dark text-white border-0">
                        <option value="all">Semua Status</option>
                        <option value="pending">Menunggu</option>
                        <option value="approved">Disetujui</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="starFilter" class="form-select bg-dark text-white border-0">
                        <option value="all">Semua Bintang</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} Bintang</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Pengulas</th>
                            <th>Produk</th>
                            <th>Komentar</th>
                            <th class="text-center">Rating</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $review->user->name ?? 'Anonim' }}</span>
                                    <small class="d-block text-muted">#{{ $review->user_id }}</small>
                                </td>
                                <td>
                                    <small>{{ \Illuminate\Support\Str::limit($review->product->name ?? 'Produk Dihapus', 30) }}</small>
                                    <small class="d-block text-muted">ID: {{ $review->product_id }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($review->comment, 50) }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="text-nowrap">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa fa-star{{ $i <= $review->rating ? ' text-warning' : ' text-muted' }}"></i>
                                        @endfor
                                        <small class="ms-1 text-muted">({{ $review->rating }}/5)</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if ($review->is_approved)
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (!$review->is_approved)
                                        <button wire:click="updateStatus({{ $review->id }}, true)"
                                                wire:confirm="Setujui ulasan ini?"
                                                class="btn btn-sm btn-success me-1" title="Setujui">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    @endif
                                    @if ($review->is_approved)
                                        <button wire:click="updateStatus({{ $review->id }}, false)"
                                                wire:confirm="Batalkan persetujuan?"
                                                class="btn btn-sm btn-warning me-1" title="Tolak">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endif
                                    <button wire:click="deleteReview({{ $review->id }})"
                                            wire:confirm="Yakin hapus ulasan ini?"
                                            class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Tidak ada ulasan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>
