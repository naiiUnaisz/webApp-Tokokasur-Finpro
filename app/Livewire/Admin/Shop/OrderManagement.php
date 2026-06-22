<?php

namespace App\Livewire\Admin\Shop;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;


#[Layout('layouts.admin')]
class OrderManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';


    public $search = '';
    public $statusFilter = ''; 

    // Properti untuk Modal Edit
    public $showEditModal = false;
    public $orderId;

    // properti untuk modal detail
    public $showDetailModal = false;
    public $detailOrder = null;


    // Properti Form Edit
    public $currentStatus;
    public $courier_name;
    public $tracking_number;
    public $notes;

    public $availableStatuses = [
        'pending' => 'Menunggu Pembayaran',
        'paid' => 'Sudah Dibayar',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    // Aturan validasi saat mengedit/mengupdate
    protected $rules = [
        'currentStatus' => 'required|in:pending,paid,processing,shipped,completed,cancelled',
        'courier_name' => 'nullable|string|max:100',
        'tracking_number' => 'nullable|string|max:100',
        'notes' => 'nullable|string|max:500',
    ];

    // Method untuk membuka modal edit
    public function openEditModal($orderId)
    {
        $order = Order::findOrFail($orderId);
        $this->orderId = $orderId;
        
        // Memuat data saat ini
        $this->currentStatus = $order->status;
        $this->courier_name = $order->courier_name;
        $this->tracking_number = $order->tracking_number;
        $this->notes = $order->notes;
        
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    public function openDetailModal($orderId)
    {
        $this->detailOrder = Order::with([
            'user',
            'address',
            'orderItems'
        ])->findOrFail($orderId);
    
        $this->showDetailModal = true;
    }
    // Method untuk menyimpan perubahan
    public function updateOrder()
    {
        $this->validate();
        
        $order = Order::findOrFail($this->orderId);
        
        $order->update([
            'status' => $this->currentStatus,
            'courier_name' => $this->courier_name,
            'tracking_number' => $this->tracking_number,
            'notes' => $this->notes,
        ]);

        $this->showEditModal = false;
        session()->flash('success', "Order #{$order->order_number} berhasil diperbarui ke status: " . $this->availableStatuses[$this->currentStatus]);
    }

    // Method untuk menghapus order
    public function deleteOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        if (in_array($order->status, ['pending', 'cancelled'])) {
             
             $order->delete();
             session()->flash('success', "Order #{$order->order_number} berhasil dihapus.");
        } else {
             session()->flash('error', "Order #{$order->order_number} tidak dapat dihapus karena statusnya '{$order->status}'.");
        }
    }

   
    public function getOrdersProperty()
    {
        
        $query = Order::query()
                     ->with(['user', 'address']) 
                     ->latest();

        // Logika Pencarian
        if ($this->search) {
            $query->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhere('total_amount', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                  });
        }
        
        // Logika Filter Status
        if ($this->statusFilter && $this->statusFilter != 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Tampilkan data per halaman 
        return $query->paginate(20);
    }

    public function render()
    {
        return view('livewire.admin.shop.order-management', [
            'orders' => $this->getOrdersProperty(),
        ]);
    }
}
