<div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 text-white">Alamat Pengiriman</h5>
            </div>

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" wire:model.live="search" placeholder="Cari penerima / user / label..." class="form-control bg-dark text-white border-0">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Label</th>
                            <th>Penerima</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Kota</th>
                            <th>Kode Pos</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($addresses as $address)
                            <tr>
                                <td><span class="fw-bold">{{ $address->user->name ?? 'N/A' }}</span></td>
                                <td>
                                    {{ $address->address_label }}
                                    @if ($address->is_default)
                                        <span class="badge bg-success ms-1">Utama</span>
                                    @else
                                        <span class="badge bg-secondary ms-1">Sekunder</span>
                                    @endif
                                </td>
                                <td>{{ $address->recipient_name }}</td>
                                <td>{{ $address->phone_number }}</td>
                                <td>
                                    <small>{{ \Illuminate\Support\Str::limit($address->address_line ?? ($address->street ?? ''), 30) }}</small>
                                </td>
                                <td>{{ $address->city }}</td>
                                <td>{{ $address->postal_code }}</td>
                                <td class="text-center">
                                    <button wire:click="showAddressDetail({{ $address->id }})" class="btn btn-sm btn-info me-1" title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button wire:click="openAddressModal({{ $address->id }})" class="btn btn-sm btn-primary me-1" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button wire:click="deleteAddress({{ $address->id }})"
                                            wire:confirm="Yakin hapus alamat ini?"
                                            class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada alamat ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $addresses->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    @if ($showAddressModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.6);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header border-bottom border-dark">
                    <h5 class="modal-title text-white">Edit Alamat</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showAddressModal', false)"></button>
                </div>
                <form wire:submit.prevent="saveAddress">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Label Alamat</label>
                            <input type="text" wire:model="address_label" class="form-control bg-dark text-white border-0">
                            @error('address_label') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Penerima</label>
                            <input type="text" wire:model="recipient_name" class="form-control bg-dark text-white border-0">
                            @error('recipient_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" wire:model="phone_number" class="form-control bg-dark text-white border-0">
                            @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Detail Alamat</label>
                            <textarea wire:model="address_line" rows="2" class="form-control bg-dark text-white border-0"></textarea>
                            @error('address_line') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label">Kota</label>
                                <input type="text" wire:model="city" class="form-control bg-dark text-white border-0">
                                @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-4">
                                <label class="form-label">Provinsi</label>
                                <input type="text" wire:model="province" class="form-control bg-dark text-white border-0">
                                @error('province') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-4">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" wire:model="postal_code" class="form-control bg-dark text-white border-0">
                                @error('postal_code') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="mt-3 form-check">
                            <input type="checkbox" wire:model="is_default" id="is_default" class="form-check-input">
                            <label for="is_default" class="form-check-label">Jadikan Alamat Utama</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-dark">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showAddressModal', false)">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal Detail --}}
    @if ($showAddressDetailModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.6);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header border-bottom border-dark">
                    <h5 class="modal-title text-white">Detail Alamat</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showAddressDetailModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="bg-dark rounded p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">{{ $address_label }}</span>
                            @if($is_default)
                                <span class="badge bg-success"><i class="fa fa-star me-1"></i>Utama</span>
                            @else
                                <span class="badge bg-secondary">Sekunder</span>
                            @endif
                        </div>
                        <p class="mb-1"><strong>Penerima:</strong> {{ $recipient_name }}</p>
                        <p class="mb-1"><strong>Telp:</strong> {{ $phone_number }}</p>
                        <p class="mb-0"><strong>Alamat:</strong><br>{{ $address_line }}, {{ $city }}, {{ $province }} - {{ $postal_code }}</p>
                    </div>
                </div>
                <div class="modal-footer border-top border-dark">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showAddressDetailModal', false)">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
