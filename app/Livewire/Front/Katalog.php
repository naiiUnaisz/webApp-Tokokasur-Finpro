<?php

namespace App\Livewire\Front;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\CartItem;
use App\Models\Wishlist;
use App\Models\JenisBusa;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;


#[Layout('layouts.landingPage')]
class Katalog extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    // Filter
    public $brand = [];
    public $foam = [];
    public $size = [];
    public $maxPrice = 15000000;

    // Sorting
    public $sort = 'default';


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query();

        // search
        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")
                ->orWhereHas('brand', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
        }

        // filter
        if (!empty($this->brand)) {
            $query->whereIn('brand_id', $this->brand);
        }

        if (!empty($this->foam)) {
            $query->whereIn('foam_type_id', $this->foam);
        }

        if (!empty($this->size)) {
            $query->whereIn('size_id', $this->size);
        }

        // price filter
        $query->where('price', '<=', $this->maxPrice);


        //  sorting

        if ($this->sort === 'price-asc') {
            $query->orderBy('price', 'asc');
        } elseif ($this->sort === 'price-desc') {
            $query->orderBy('price', 'desc');
        }

        return view('livewire.front.katalog', [
            'products' => $query->paginate(10),
            'brands'   => Brand::all(),
            'foams'    => JenisBusa::all(),
            'sizes'    => Size::all(),
        ]);
    }

    public function addToCart($produkId)
    {
        if (!Auth::check()) {
            return redirect()->guest('login');
        }

        $cartItem = CartItem::where('user_id', Auth::id())
                    ->where('produk_id', $produkId)
                    ->first();

         if ($cartItem) {
             $cartItem->increment('quantity');
        } else {
                CartItem::create([
                'user_id'   => Auth::id(),
                'produk_id'=> $produkId,
                'quantity' => 1
            ]);
        }
        
        $this->dispatch('cartUpdated'); 
        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function addWishlist($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
        ->where('product_id', $productId)
        ->first();

        if ($wishlist) {
            $wishlist->delete();
            session()->flash('info', 'item dihapus dari wishlist.');
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);
        
            session()->flash('success', 'item ditambahkan ke wishlist.');
        }
    }   
}
