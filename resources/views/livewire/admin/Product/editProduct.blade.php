@if($showModal)
<div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.6);">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content bg-secondary text-white">
            <div class="modal-header border-bottom border-dark">
                <h5 class="modal-title text-white">
                    {{ $isEditing ? 'Edit Produk: ' . $name : 'Tambah Produk Baru' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
            </div>
            <form wire:submit.prevent="{{ $isEditing ? 'updateProduct' : 'storeProduct' }}">
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-primary border-bottom border-dark pb-2 mb-3">Data Produk</h6>
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" wire:model.live="name" class="form-control bg-dark text-white border-0">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slug (otomatis)</label>
                                <input type="text" wire:model.defer="slug" class="form-control bg-dark text-white border-0" readonly>
                                @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea wire:model.defer="deskripsi" rows="3" class="form-control bg-dark text-white border-0"></textarea>
                                @error('deskripsi') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary border-bottom border-dark pb-2 mb-3">Kategori & Varian</h6>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label">Kategori</label>
                                    <select wire:model.defer="kategori_id" class="form-select bg-dark text-white border-0">
                                        <option value="">Pilih...</option>
                                        @foreach($categories as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Brand</label>
                                    <select wire:model.defer="brand_id" class="form-select bg-dark text-white border-0">
                                        <option value="">Pilih...</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Busa</label>
                                <select wire:model.defer="foam_type_id" class="form-select bg-dark text-white border-0">
                                    <option value="">Pilih...</option>
                                    @foreach($foam_types as $foamType)
                                        <option value="{{ $foamType->id }}">{{ $foamType->name }}</option>
                                    @endforeach
                                </select>
                                @error('foam_type_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <h6 class="text-primary border-bottom border-dark pb-2 mb-3 mt-4">Harga & Stok</h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Ukuran</label>
                                    <select wire:model.defer="size_id" class="form-select bg-dark text-white border-0">
                                        <option value="">Pilih...</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('size_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input type="number" wire:model.defer="price" class="form-control bg-dark text-white border-0" min="0">
                                    @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Stok</label>
                                    <input type="number" wire:model.defer="stock_quantity" class="form-control bg-dark text-white border-0" min="0">
                                    @error('stock_quantity') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label">SKU</label>
                                    <input type="text" wire:model.defer="sku" class="form-control bg-dark text-white border-0">
                                    @error('sku') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-dark">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i>{{ $isEditing ? 'Simpan Perubahan' : 'Simpan Produk' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
