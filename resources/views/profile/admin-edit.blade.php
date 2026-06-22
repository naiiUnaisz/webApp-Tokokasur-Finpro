@extends('layouts.admin')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white">Profil Saya</h5>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>Profil berhasil diperbarui.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="bg-secondary rounded p-4 mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-md-2 text-center">
                    <div class="position-relative d-inline-block">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('Frontend/dashboard/img/user.jpg') }}"
                             alt="Avatar" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-2"></div>
                    </div>
                </div>
                <div class="col-md-10">
                    <h5 class="text-white mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <form method="post" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-md-6">
                                <input type="file" name="avatar" class="form-control bg-dark text-white border-0" accept="image/*" required>
                                @error('avatar') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-upload me-1"></i>Ganti Foto</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-secondary rounded p-4 mb-4">
            <h6 class="text-white mb-3"><i class="fa fa-user-circle me-2"></i>Informasi Profil</h6>
            <form method="post" action="{{ route('profile.update') }}" class="mt-3">
                @csrf
                @method('patch')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control bg-dark text-white border-0" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control bg-dark text-white border-0" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>

        <div class="bg-secondary rounded p-4 mb-4">
            <h6 class="text-white mb-3"><i class="fa fa-lock me-2"></i>Ubah Password</h6>
            <form method="post" action="{{ route('password.update') }}" class="mt-3">
                @csrf
                @method('put')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control bg-dark text-white border-0" required>
                        @error('current_password', 'updatePassword') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control bg-dark text-white border-0" required>
                        @error('password', 'updatePassword') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control bg-dark text-white border-0" required>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Simpan</button>
                </div>
                @if (session('status') === 'password-updated')
                    <small class="text-success ms-3">Password berhasil diubah.</small>
                @endif
            </form>
        </div>

        <div class="bg-secondary rounded p-4">
            <h6 class="text-danger mb-3"><i class="fa fa-exclamation-triangle me-2"></i>Hapus Akun</h6>
            <p class="text-muted">Setelah akun dihapus, semua data akan terhapus permanen.</p>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fa fa-trash me-1"></i>Hapus Akun
            </button>

            <div class="modal fade" id="deleteModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-secondary text-white">
                        <div class="modal-header border-bottom border-dark">
                            <h5 class="modal-title text-white">Konfirmasi Hapus Akun</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="post" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('delete')
                            <div class="modal-body">
                                <p>Masukkan password untuk mengonfirmasi penghapusan akun:</p>
                                <input type="password" name="password" class="form-control bg-dark text-white border-0" placeholder="Password" required>
                                @error('password', 'userDeletion') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="modal-footer border-top border-dark">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash me-1"></i>Hapus Akun</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
