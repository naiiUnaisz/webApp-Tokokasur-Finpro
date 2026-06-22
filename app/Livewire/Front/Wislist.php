<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Models\CartItem;
use App\Models\Wishlist;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.landingPage')]
class Wislist extends Component
{

    public $wishlistId = [];

    public function mount()
    {
        
        $this->wishlistId = Wishlist::where('user_id', Auth::id())
            ->pluck('product_id')
            ->toArray();
    }

    public function render()
    {
        $items = Wishlist::where('user_id', Auth::id())
        ->with('product.size')
        ->get();

        return view('livewire.front.wislist', compact('items'));
    }


        public function deleteWishlist($productId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

            $this->wishlistId = array_filter($this->wishlistId, function($id) use ($productId) {
                return $id !== $productId;
            });

        session()->flash('success', 'Item Berhasil Dihapus dari Wishlist.');
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
        $cartItem->quantity += 1;
        $cartItem->save();
        $this->dispatch('cartUpdated');
        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang');
        return;
    }

    CartItem::create([
        'user_id' => Auth::id(),
        'produk_id' => $produkId,
        'quantity' => 1
    ]);

    $this->dispatch('cartUpdated');
    session()->flash('success', 'Produk berhasil ditambahkan ke keranjang');
}
}
