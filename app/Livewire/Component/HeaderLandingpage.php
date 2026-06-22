<?php

namespace App\Livewire\Component;

use Livewire\Component;
use App\Models\CartItem;
use App\Models\Wishlist;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class HeaderLandingpage extends Component
{

    public $cartCount = 0;
    public $wishlistCount = 0;

    #[On('cartUpdated')]
    #[On('wishlistUpdated')]

    public function mount()
    {
        $this->loadCounts();
    }

    public function loadCounts()
    {
        if (Auth::check()) {
            $this->cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
            $this->wishlistCount = Wishlist::where('user_id', Auth::id())->count();
        }
    }

    
    public function render()
    {

        return view('livewire.component.header-landingpage');
    }
}
