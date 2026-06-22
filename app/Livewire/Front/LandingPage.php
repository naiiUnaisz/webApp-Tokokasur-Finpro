<?php

namespace App\Livewire\Front;

use App\Models\Review;
use App\Models\Product;
use Livewire\Component;
use App\Models\CartItem;
use App\Models\Wishlist;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.landingPage')]

class LandingPage extends Component
{
    public function render()
    {

        $reviews = Review::with('user')
        ->where('is_approved', 1)
        ->latest()
        ->take(3)
        ->get();

        $products = Product::with('primaryImage')
        ->latest()
        ->take(3)
        ->get();

        return view('livewire.front.landing-page', [
        'products' => $products
        ,'reviews' => $reviews
        
    ]);
    }

    // Menambahkan produk ke keranjang
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
