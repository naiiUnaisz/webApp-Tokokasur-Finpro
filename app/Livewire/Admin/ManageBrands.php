<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]

class ManageBrands extends Component
{
    #[Rule('required|min:3', message: 'Nama brand wajib diisi minimal 3 huruf')]
    public $name = '';

    
    #[Rule('required|min:3', message: 'Nama brand wajib diisi')]
    
    public $editingBrandName;

    public $editingBrandId = null;

    
    public function render()
    {
        $brands = Brand::all();

        return view('livewire.admin.manage-brands', compact('brands'));
    }

    public function createBrand()
    {
        
        $this->validateOnly('name'); 

        Brand::create([
            'name' => $this->name, 
            'slug' => Str::slug($this->name) 
        ]);

        $this->reset('name'); 
        session()->flash('Create', 'Berhasil menambahkan brand');
    }

 
    public function updateBrand()
    {
        
        $this->validateOnly('editingBrandName'); 
        $brand = Brand::find($this->editingBrandId);

        $brand->update([
            'name' => $this->editingBrandName, 
            'slug' => Str::slug($this->editingBrandName)
        ]);

        session()->flash('Update', 'Brand berhasil diperbarui.');
        $this->cancelEditBrand(); 
    }


    public function deleteBrand($brandID)
    {
        $brand = Brand::find($brandID);
        $brand->delete();

        session()->flash('Delete', 'Brand berhasil dihapus.');
    }

   
    public function editBrand($brandId)
    {
        $brand = Brand::find($brandId);
        $this->editingBrandId = $brandId;
        $this->editingBrandName = $brand->name;
    }

    public function cancelEditBrand()
    {
        
        $this->reset('editingBrandId', 'editingBrandName');
    }
}
