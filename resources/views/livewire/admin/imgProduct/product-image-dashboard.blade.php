<div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 text-white">Kelola Gambar Produk</h5>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari produk..." class="form-control bg-dark text-white border-0">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Jumlah Gambar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td class="fw-bold">{{ $product->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $product->images()->count() }}</span>
                                </td>
                                <td class="text-center">
                                    <button wire:click="goToManageImage({{ $product->id }})" class="btn btn-sm btn-primary">
                                        <i class="fa fa-images me-1"></i>Kelola Gambar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tidak ada produk ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
