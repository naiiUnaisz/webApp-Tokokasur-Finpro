<?php

namespace App\Livewire\Admin;

use App\Models\Size;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]

class ManageSizes extends Component
{

    #[Rule('required|min:3', message: 'Nama Ukuran wajib diisi minimal 3 huruf')]
    public $name = '';

    
    #[Rule('required|min:3', message: 'Nama Ukuran wajib diisi')]
    
    public $editingSizeName;

    public $editingSizeId = null;
    
    public function render()
    {
        $sizes = Size::all();

        return view('livewire.admin.manage-sizes', compact('sizes'));
    }

    public function createSize()
    {
        
        $this->validateOnly('name'); 

        Size::create([
            'name' => $this->name, 
            'slug' => Str::slug($this->name) 
        ]);

        $this->reset('name'); 
        session()->flash('Create', 'Berhasil menambahkan Ukuran');
    }

 
    public function updateSize()
    {
        
        $this->validateOnly('editingSizeName'); 
        $size = Size::find($this->editingSizeId);

        $size->update([
            'name' => $this->editingSizeName, 
            'slug' => Str::slug($this->editingSizeName)
        ]);

        session()->flash('Update', 'Ukuran berhasil diperbarui.');
        $this->cancelEditSize(); 
    }


    public function deleteSize($sizeID)
    {
        $size = Size::find($sizeID);
        $size->delete();

        session()->flash('Delete', 'Ukuran berhasil dihapus.');
    }

   
    public function editSize($sizeId)
    {
        $size = Size::find($sizeId);
        $this->editingSizeId = $sizeId;
        $this->editingSizeName = $size->name;
    }

    public function cancelEditSize()
    {
        
        $this->reset('editingSizeId', 'editingSizeName');
    }
}
