<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <h3 class="mb-4 text-white">Kelola Ukuran</h3>

        @if (session('Update'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('Update') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('Create'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('Create') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('Delete'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('Delete') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form wire:submit="createSize" class="mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label for="name" class="form-label text-white">Ukuran Baru</label>
                    <input type="text" wire:model="name" id="name" class="form-control bg-dark text-white border-0">
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sizes as $item)
                        <tr wire:key="{{ $item->id }}">
                            <td>{{ $item->id }}</td>
                            <td>
                                @if ($editingSizeId === $item->id)
                                    <input type="text" wire:model="editingSizeName" class="form-control bg-dark text-white border-0">
                                    @error('editingSizeName') <small class="text-danger">{{ $message }}</small> @enderror
                                @else
                                    {{ $item->name }}
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($editingSizeId === $item->id)
                                    <button wire:click="updateSize" class="btn btn-sm btn-success">Simpan</button>
                                    <button wire:click="cancelEditSize" class="btn btn-sm btn-secondary">Batal</button>
                                @else
                                    <button wire:click="editSize({{ $item->id }})" class="btn btn-sm btn-info">Edit</button>
                                    <button wire:click="deleteSize({{ $item->id }})" 
                                            wire:confirm="Yakin ingin menghapus?"
                                            class="btn btn-sm btn-danger">Hapus</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
