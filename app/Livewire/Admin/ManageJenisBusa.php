<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\JenisBusa;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]

class ManageJenisBusa extends Component
{

    #[Rule('required|min:3', message: 'Nama Jenis Busa wajib diisi minimal 3 huruf')]
    public $name = '';

    
    #[Rule('required|min:3', message: 'Nama Jenis Busa wajib diisi')]
    
    public $editingTypeName;

    public $editingTypeId = null;

    public function render()
    {
        $FoamTypes = JenisBusa::all();

        return view('livewire.admin.manage-jenis-busa',compact('FoamTypes'));
    }

    public function createFoamType()
    {
        
        $this->validateOnly('name'); 

        JenisBusa::create([
            'name' => $this->name, 
            'slug' => Str::slug($this->name) 
        ]);

        $this->reset('name'); 
        session()->flash('Create', 'Berhasil menambahkan Jenis Busa');
    }

 
    public function updateFoamType()
    {
        
        $this->validateOnly('editingTypeName'); 
        $FoamType = JenisBusa::find($this->editingTypeId);

        $FoamType->update([
            'name' => $this->editingTypeName, 
            'slug' => Str::slug($this->editingTypeName)
        ]);

        session()->flash('Update', 'Jenis Busa berhasil diperbarui.');
        $this->cancelEditFoamType(); 
    }


    public function deleteFoamType($brandID)
    {
        $FoamType = JenisBusa::find($brandID);
        $FoamType->delete();

        session()->flash('Delete', 'Jenis busa berhasil dihapus.');
    }

   
    public function editFoamType($FoamTypeId)
    {
        $FoamType = JenisBusa::find($FoamTypeId);
        $this->editingTypeId = $FoamTypeId;
        $this->editingTypeName = $FoamType->name;
    }

    public function cancelEditFoamType()
    {
        
        $this->reset('editingTypeId', 'editingTypeName');
    }
}
