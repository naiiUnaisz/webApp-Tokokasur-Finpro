<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;


#[Layout('layouts.admin')]

class ProductImageDashboard extends Component
{
    public function render()
    {

        $products = Product::where('name', 'like', '%' . $this->search . '%')
                           ->orWhere('slug', 'like', '%' . $this->search . '%')
                           ->orderBy('id', 'desc')
                           ->paginate(10);

        return view('livewire.admin.imgProduct.product-image-dashboard',  [
            'products' => $products,
        ]);
    }

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

   
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    
    public function goToManageImage($productId)
    {
       
        return redirect()->route('admin.images', ['productId' => $productId]); 
    }


}
