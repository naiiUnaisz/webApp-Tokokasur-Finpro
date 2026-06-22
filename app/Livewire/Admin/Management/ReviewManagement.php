<?php

namespace App\Livewire\Admin\Management;

use App\Models\Review;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ReviewManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    // Properti untuk Pencarian dan Filter
    #[Url(history: true)]
    public $search = '';
    #[Url(history: true)]
    public $statusFilter = 'all'; 
    #[Url(history: true)]
    public $starFilter = 'all'; 

    public function render()
    {
        return view('livewire.admin.management.review-management', [
            'reviews' => $this->getReviewsProperty(),
        ]);
    }



    // Method untuk mengambil data Ulasan
    public function getReviewsProperty()
    {
        
        $query = Review::query();

        if (!empty($this->search)) {
    
            $query->where('comment', 'like', '%' . $this->search . '%')
                  ->orWhere('product_id', (int)$this->search); // Mencari berdasarkan ID produk
        }

        // Logika Filter Status

        if ($this->statusFilter === 'approved') {
            $query->where('is_approved', true);
        } elseif ($this->statusFilter === 'pending') {
            $query->where('is_approved', false);
        }

        // Logika Filter Rating Bintang
        if ($this->starFilter !== 'all') {
            $query->where('rating', (int)$this->starFilter);
        }

        return $query->with(['user', 'product'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
    }


    // Method untuk mengupdate status ulasan 
    public function updateStatus($reviewId, $isApproved)
    {
        try {
            $review = Review::findOrFail($reviewId);
            $review->is_approved = $isApproved;
            
            if ($isApproved) {
                $review->approved_byadmin_id = Auth::id(); 
                $message = 'Disetujui';
            } else {
                $review->approved_byadmin_id = null;
                $message = 'Ditolak/Pending';
            }

            $review->save();

            session()->flash('success', 'Ulasan dari ' . ($review->user->name ?? 'Anonim') . ' berhasil diubah menjadi ' . $message . '!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengubah status ulasan: ' . $e->getMessage());
        }

        $this->resetPage();
    }

    // Method untuk menghapus ulasan
    public function deleteReview($reviewId)
    {
        try {
            $review = Review::findOrFail($reviewId);
            $review->delete();

            session()->flash('success', 'Ulasan berhasil dihapus secara permanen.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus ulasan: ' . $e->getMessage());
        }

        $this->resetPage();
    }
}
