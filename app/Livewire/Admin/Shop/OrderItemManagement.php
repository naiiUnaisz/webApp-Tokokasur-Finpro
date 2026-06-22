<?php

namespace App\Livewire\Admin\Shop;

use Livewire\Component;
use App\Models\OrderItem;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;


#[Layout('layouts.admin')]
class OrderItemManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public $orderIdFilter = null;

    public function render()
    {
        return view('livewire.admin.shop.order-item-management', [
            'items' => $this->getOrderItemsProperty(),
        ]);
    }

    public function getOrderItemsProperty()
    {

        $query = OrderItem::query()
                     ->with(['order', 'product']) 
                     ->latest('id');

        // Logika Pencarian
        if ($this->search) {
            $query->where('product_name_snapshot', 'like', '%' . $this->search . '%')
                  ->orWhere('produk_id', $this->search);
        }

        if ($this->orderIdFilter) {
            $query->where('order_id', $this->orderIdFilter);
        }
        
        return $query->paginate(20);
    }

    
    public function deleteItem($itemId)
    {
        $item = OrderItem::with('order')->findOrFail($itemId);

        if (in_array($item->order->status, ['pending', 'cancelled'])) {
             $item->delete();
             session()->flash('success', "Item '{$item->product_name_snapshot}' dari Order #{$item->order->order_number} berhasil dihapus.");
        } else {
             session()->flash('error', "Item tidak dapat dihapus karena status Order sudah '{$item->order->status}'.");
        }
    }
}
