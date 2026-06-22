<div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 text-white">Kelola Produk</h5>
                <button wire:click="createProduct" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus me-1"></i>Tambah Produk
                </button>
            </div>

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari produk (nama/SKU)..." class="form-control bg-dark text-white border-0">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="perPage" class="form-select bg-dark text-white border-0">
                        <option value="5">5 per hal</option>
                        <option value="10">10 per hal</option>
                        <option value="25">25 per hal</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Brand</th>
                            <th>Varian</th>
                            <th>Stok</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $product->name }}</div>
                                    <small class="text-muted">SKU: {{ $product->sku ?? '-' }}</small>
                                </td>
                                <td>{{ $product->kategori->name ?? '-' }}</td>
                                <td>{{ $product->brand->name ?? '-' }}</td>
                                <td>{{ $product->size->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $product->stock_quantity > 0 ? 'success' : 'danger' }}">
                                        {{ $product->stock_quantity }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-info">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <button wire:click="editProduct({{ $product->id }})" class="btn btn-sm btn-info me-1" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button wire:click="deleteProduct({{ $product->id }})"
                                            onclick="confirm('Yakin hapus produk ini?') || event.stopImmediatePropagation()"
                                            class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada produk ditemukan.</td>
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

    @include('livewire.admin.Product.editProduct')
</div>
