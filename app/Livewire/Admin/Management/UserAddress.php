<?php

namespace App\Livewire\Admin\Management;

use App\Models\AlamatUser;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;


#[Layout('layouts.admin')]

class UserAddress extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $sortField = 'created_at';
    public $sortAsc = false;

    public $showAddressModal = false;
    public $showAddressDetailModal = false;
    public $addressId = null;
    public $userId; 
    
    public $address_label, $recipient_name, $phone_number, $address_line, $city, $province, $postal_code, $is_default = false;

    protected $rules = [
        'address_label' => 'required|string|max:50', 
        'recipient_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:15',
        'address_line' => 'required|string|max:255', 
        'city' => 'required|string|max:100',
        'province' => 'required|string|max:100',
        'postal_code' => 'required|string|max:10',
        'is_default' => 'boolean',
    ];

    public function render()
    {
        // mencari user
        $addresses = AlamatUser::query()
            ->with('user') 
            ->when($this->search, function ($query) {

                $query->where('recipient_name', 'like', '%' . $this->search . '%')
                      ->orWhere('address_label', 'like', '%' . $this->search . '%')
                      ->orWhere('address_line', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate(10);

        return view('livewire.admin.management.user-address', [
            'addresses' => $addresses,
        ]);
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    
        public function openAddressModal($addressId)
    {
        $this->reset([
            'addressId',
            'userId',
            'address_label',
            'recipient_name',
            'phone_number',
            'address_line',
            'city',
            'province',
            'postal_code',
            'is_default',
        ]);

        $this->addressId = $addressId;

        $address = AlamatUser::findOrFail($addressId);

        $this->address_label  = $address->address_label;
        $this->recipient_name = $address->recipient_name;
        $this->phone_number   = $address->phone_number;
        $this->address_line   = $address->address_line;
        $this->city           = $address->city;
        $this->province       = $address->province;
        $this->postal_code    = $address->postal_code;
        $this->is_default     = (bool) $address->is_default;
        $this->userId         = $address->user_id;

        $this->showAddressModal = true;
    }

    public function saveAddress()
    {
        if (!$this->addressId) {
            return;
        }

        $this->validate();

        AlamatUser::findOrFail($this->addressId)->update([
            'user_id'        => $this->userId,
            'address_label'  => $this->address_label,
            'recipient_name' => $this->recipient_name,
            'phone_number'   => $this->phone_number,
            'address_line'   => $this->address_line,
            'city'           => $this->city,
            'province'       => $this->province,
            'postal_code'    => $this->postal_code,
            'is_default'     => $this->is_default,
        ]);

        $this->showAddressModal = false;

        session()->flash('success', 'Alamat berhasil diperbarui.');
    }

    public function showAddressDetail($addressId)
    {
        $address = AlamatUser::findOrFail($addressId);
    
        $this->addressId       = $address->id;
        $this->address_label  = $address->address_label;
        $this->recipient_name = $address->recipient_name;
        $this->phone_number   = $address->phone_number;
        $this->address_line   = $address->address_line;
        $this->city           = $address->city;
        $this->province       = $address->province;
        $this->postal_code    = $address->postal_code;
        $this->is_default     = (bool) $address->is_default;
    
        $this->showAddressDetailModal = true;
    }
    
    public function deleteAddress($id)
    {
        AlamatUser::where('id', $id)->delete();

         session()->flash('success', 'Alamat berhasil dihapus');
    }
}
