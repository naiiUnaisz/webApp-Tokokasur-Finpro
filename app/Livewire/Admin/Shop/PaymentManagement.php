<?php

namespace App\Livewire\Admin\Shop;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;


#[Layout('layouts.admin')]
class PaymentManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Properti untuk Pencarian dan Filter
    public $search = '';
    public $statusFilter = 'all';

    public $availableStatuses = [
        'pending' => 'Menunggu Verifikasi',
        'verified' => 'Disetujui',
        'rejected' => 'Ditolak',
    ];

    public function getConfirmationsProperty()
    {
        $query = Payment::query();

        // Logika Pencarian
        if (!empty($this->search)) {

            $query->where(function ($q) {
                $q->where('bank_name', 'like', '%' . $this->search . '%')
                  ->orWhere('payment_method', 'like', '%' . $this->search . '%');
            });

        }

        // Logika Filter Status
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
        
                return $query->with('order') 
                ->orderBy('created_at', 'desc')
                ->paginate(10);
    }

    public function render()
    {
        
        return view('livewire.admin.shop.payment-management', [
            'payments' => $this->getConfirmationsProperty(), 
            'availableStatuses' => $this->availableStatuses, 
        ]);
    }

    public function updateStatus($paymentId, $status)
    {
        try {
            $payment = Payment::findOrFail($paymentId);
            $payment->status = $status;
            $payment->save();

            // Jika disetujui, update status order menjadi 'verified'
            if ($status === 'verified' && $payment->order) {
                $order = $payment->order;
                $order->status = 'completed'; 
                $order->save();
            }
            

            session()->flash('success', 'Status Konfirmasi Pembayaran berhasil diubah menjadi ' . ucfirst($status) . '!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengubah status: ' . $e->getMessage());
        }

        // Reset halaman pagination ke-1 setelah aksi
        $this->resetPage();
    }
    
}