<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]

class ManageKategori extends Component
{
    #[Rule('required|min:3', message: 'Nama kategori wajib diisi minimal 3 huruf')]
    public $name = '';

    
    #[Rule('required|min:3', message: 'Nama kategori wajib diisi')]
    
    public $editingKategoriName;

    public $editingKategoriId = null;

    
    public function render()
    {
        $kategories = Kategori::all();

        return view('livewire.admin.manage-kategori', compact('kategories'));
    }

    public function createKategori()
    {
        
        $this->validateOnly('name'); 

        Kategori::create([
            'name' => $this->name, 
            'slug' => Str::slug($this->name) 
        ]);

        $this->reset('name'); 
        session()->flash('Create', 'Berhasil menambahkan kategori');
    }

 
    public function updateKategori()
    {
        
        $this->validateOnly('editingKategoriName'); 
        $kategori = Kategori::find($this->editingKategoriId);

        $kategori->update([
            'name' => $this->editingKategoriName, 
            'slug' => Str::slug($this->editingKategoriName)
        ]);

        session()->flash('Update', 'Kategori berhasil diperbarui.');
        $this->cancelEdit(); 
    }


    public function deleteKategori($kategoriID)
    {
        $kategori = Kategori::find($kategoriID);
        $kategori->delete();

        session()->flash('Delete', 'Kategori berhasil dihapus.');
    }

   
    public function editKategori($kategoriId)
    {
        $kategori = Kategori::find($kategoriId);
        $this->editingKategoriId = $kategoriId;
        $this->editingKategoriName = $kategori->name;
    }

    public function cancelEdit()
    {
        
        $this->reset('editingKategoriId', 'editingKategoriName');
    }
    
    

}
