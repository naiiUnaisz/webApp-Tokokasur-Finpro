<div>
    <div class="container-fluid pt-4 px-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white">Kelola Gambar Produk</h5>
            <a href="{{ route('admin.imageDashboard') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <div class="bg-secondary rounded p-4 mb-4">
            <h6 class="text-primary mb-1">{{ $product->name }}</h6>
            <small class="text-muted">ID Produk: {{ $product->id }}</small>
        </div>

        <div class="bg-secondary rounded p-4 mb-4">
            <h6 class="text-white mb-3">Unggah Gambar Baru</h6>
            <form wire:submit.prevent="uploadImages">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Pilih File (maks 5)</label>
                        <input type="file" wire:model="images" id="images" multiple class="form-control bg-dark text-white border-0">
                        @error('images') <small class="text-danger">{{ $message }}</small> @enderror
                        @error('images.*') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alt Text (SEO)</label>
                        <input type="text" wire:model.defer="alt_text" class="form-control bg-dark text-white border-0" placeholder="Deskripsi singkat gambar">
                        @error('alt_text') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                @if ($images)
                    <div class="mt-3 d-flex gap-2 flex-wrap p-2 border border-dark rounded">
                        @foreach ($images as $image)
                            <div style="width:80px;height:80px;">
                                <img src="{{ $image->temporaryUrl() }}" class="w-100 h-100 rounded" style="object-fit:cover;">
                            </div>
                        @endforeach
                    </div>
                @endif

                <button type="submit" class="btn btn-primary mt-3" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="images"><i class="fa fa-upload me-1"></i>Unggah & Simpan</span>
                    <span wire:loading wire:target="images">Mengunggah...</span>
                </button>
            </form>
        </div>

        <div class="bg-secondary rounded p-4">
            <h6 class="text-white mb-3">Gambar Tersimpan ({{ $productImages->count() }})</h6>
            @if ($productImages->isEmpty())
                <p class="text-muted mb-0">Belum ada gambar. Silakan unggah di atas.</p>
            @else
                <div class="row g-3">
                    @foreach ($productImages as $image)
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="position-relative bg-dark rounded overflow-hidden" style="height:140px;">
                                <img src="{{ Storage::url($image->image_url) }}" alt="{{ $image->alt_text ?: $product->name }}" class="w-100 h-100" style="object-fit:cover;">
                                @if ($image->is_primary)
                                    <span class="position-absolute top-0 start-0 badge bg-success m-1">UTAMA</span>
                                @endif
                                <div class="position-absolute bottom-0 start-0 end-0 p-1 text-center" style="background:rgba(0,0,0,.6);">
                                    @if (!$image->is_primary)
                                        <button wire:click="setMainImage({{ $image->id }})" class="btn btn-sm btn-primary me-1" title="Jadikan Utama">
                                            <i class="fa fa-star"></i>
                                        </button>
                                    @endif
                                    <button wire:click="confirmImageDeletion({{ $image->id }})" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if ($isDeleting)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.6);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header border-bottom border-dark">
                    <h5 class="modal-title text-white">Konfirmasi Hapus</h5>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menghapus gambar ini?</p>
                </div>
                <div class="modal-footer border-top border-dark">
                    <button wire:click="cancelImageDeletion" class="btn btn-secondary">Batal</button>
                    <button wire:click="deleteImage" class="btn btn-danger"><i class="fa fa-trash me-1"></i>Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
