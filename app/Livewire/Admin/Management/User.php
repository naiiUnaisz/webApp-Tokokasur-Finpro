<?php

namespace App\Livewire\Admin\Management;

use App\Models\User as UserModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class User extends Component
{
    
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $sortField = 'created_at';
    public $sortAsc = false;
    
    // Detail Pengguna
    public $showDetailModal = false;
    public $showEditModal = false;
    public ?UserModel $selectedUser = null; 
    public $name = '';
    public $email = '';
  
    public $availableRoles = ['admin', 'Customer'];
    public $newRole ; 

    public function render()
    {
        $users = UserModel::query()
        ->when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
        ->paginate(10);

        return view('livewire.admin.management.user', [
            'users' => $users,
        ]);
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showUserDetail($userId)
    {
        $this->selectedUser = UserModel::findOrFail($userId);
        $this->newRole = $this->selectedUser->role; 
        $this->showDetailModal = true;
    }
    
    
    public function updateRole($userId)
    {
        $this->validate([
            'newRole' => 'required|in:admin,Customer',
        ]);
        
        $user = UserModel::findOrFail($userId);
        
        if ($user->role !== $this->newRole) {
            $user->role = $this->newRole;
            $user->save();
            
            if ($this->selectedUser && $this->selectedUser->id === $userId) {
                $this->selectedUser->role = $this->newRole;
            }
            
            session()->flash('success', 'Role untuk ' . $user->name . ' berhasil diubah menjadi ' . $this->newRole . '.');
        } else {
             session()->flash('success', 'Tidak ada perubahan role.');
        }

        $this->showEditModal = false;
    }
   
        public function editUser($userId)
    {
        $this->selectedUser = UserModel::findOrFail($userId);

        $this->name    = $this->selectedUser->name;
        $this->email   = $this->selectedUser->email;
        $this->newRole = $this->selectedUser->role;

        $this->showEditModal = true;
    }

    public function updateUser()
    {
        $this->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'newRole' => 'required|in:admin,Customer',
        ]);
    
        $this->selectedUser->update([
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->newRole,
        ]);
    
        $this->reset(['name', 'email', 'newRole', 'selectedUser']);
        $this->showEditModal = false;
    
        session()->flash('success', 'Data user berhasil diperbarui');
    }
    

    public function deleteUser($userId)
        {
            $user = UserModel::findOrFail($userId);
            $user->delete();

            session()->flash('success', 'Pengguna ' . $user->name . ' berhasil dihapus.');
            $this->showDetailModal = false;
        }
}
