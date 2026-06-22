<div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 text-white">Manajemen Pengguna</h5>
            </div>

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." class="form-control bg-dark text-white border-0">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th class="text-center">Role</th>
                            <th>Bergabung</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="fw-bold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'success' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <button wire:click="showUserDetail({{ $user->id }})" class="btn btn-sm btn-info me-1" title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button wire:click="editUser({{ $user->id }})" class="btn btn-sm btn-primary me-1" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button wire:click="deleteUser({{ $user->id }})" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Tidak ada pengguna ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    @if ($showDetailModal && $selectedUser)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.6);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header border-bottom border-dark">
                    <h5 class="modal-title text-white">Detail: {{ $selectedUser->name }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showDetailModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="bg-dark rounded p-3">
                        <p><strong>Email:</strong> {{ $selectedUser->email }}</p>
                        <p><strong>Bergabung:</strong> {{ $selectedUser->created_at->format('d F Y') }}</p>
                        <p>
                            <strong>Role:</strong>
                            <span class="badge bg-{{ $selectedUser->role === 'admin' ? 'primary' : 'success' }}">{{ $selectedUser->role }}</span>
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-top border-dark">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showDetailModal', false)">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal Edit --}}
    @if ($showEditModal && $selectedUser)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.6);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header border-bottom border-dark">
                    <h5 class="modal-title text-white">Edit: {{ $selectedUser->name }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showEditModal', false)"></button>
                </div>
                <form wire:submit.prevent="updateUser">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" wire:model.defer="name" class="form-control bg-dark text-white border-0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" wire:model.defer="email" class="form-control bg-dark text-white border-0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select wire:model.defer="newRole" class="form-select bg-dark text-white border-0">
                                @foreach ($availableRoles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-dark">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showEditModal', false)">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
