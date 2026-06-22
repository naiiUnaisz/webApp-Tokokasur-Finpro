<?php

namespace App\Livewire\Front;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

#[Layout('layouts.landingPage')]
class Checkout extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';
    #[Url(history: true)]
    public $statusFilter = 'all';

    public $showTrackingModal = false;
    public $showModal = false;
    public $selectedOrder;
    public $trackingOrder;
    public $trackingData = [];

    // reset pagination saat search berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function filterStatus($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function render()
    {
        $query = Order::where('user_id', Auth::id())
        ->with(['orderItems.product.images'])
            ->latest();

        // FILTER SEARCH berdasarkan nama produk
        if ($this->search) {
            $query->whereHas('orderItems.product', function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            });
        }

        // FILTER STATUS ORDERS
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // FINAL QUERY
        $orders = $query->paginate(10);

        return view('livewire.front.checkout', [
            'orders' => $orders,
        ]);
    }

    // buka modal lacak
    public function track($orderId)
    {
        $this->resetModals();

        $this->trackingOrder = Order::with(['orderItems.product'])->findOrFail($orderId);

        // dummy tracking sementara
        $this->trackingData = [
            [
                'title' => 'Paket Dibuat - Booking',
                'time' => '2025-12-06 18:32',
                'done' => true,
            ],
            [
                'title' => 'Paket sedang disiapkan penjual',
                'time' => '2025-12-06 18:35',
                'done' => false,
            ]
        ];

        $this->showTrackingModal = true;
    }

    private function resetModals()
    {
        $this->showModal = false;
        $this->showTrackingModal = false;
    }
    
    public function closeModal()
    {
        $this->resetModals();
    }


    public function showDetail($orderId)
    {
        $this->resetModals();

        $this->selectedOrder = Order::with(
            'orderItems.product',
            'payment'
        )->find($orderId);
        $this->showModal = true;
    }

    
}