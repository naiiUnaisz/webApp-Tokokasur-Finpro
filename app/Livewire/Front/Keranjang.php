<?php

namespace App\Livewire\Front;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.landingPage')]
class Keranjang extends Component
{
    public $cartItems = [];
    public $subtotal = 0;

    public function mount()
    {
        $this->loadCart();
    }

    public function render()
    {
        return view('livewire.front.keranjang');
    }

    private function loadCart()
    {
        if (!Auth::check()) {
            $this->cartItems = [];
            return;
        }

        $dbItems = CartItem::where('user_id', Auth::id())
            ->with(['product', 'product.primaryImage', 'product.size'])
            ->get();

        
        $cart = [];

        foreach ($dbItems as $item) {
            $cart[$item->id] = [
                'product_id' => $item->product->id,
                'name'      => $item->product->name,
                'price'     => $item->product->price,
                'quantity'  => $item->quantity,
                'size'      => $item->product->size->name ?? '-',
                'image_url' => $item->product->primaryImage->image_url 
                                ?? asset('Frontend/landingPage_TokoKasur/img/default.jpg'),
            ];
        }

        $this->cartItems = $cart;

        $this->calculateTotals();
    }

    private function calculateTotals()
    {
        $this->subtotal = 0;

        foreach ($this->cartItems as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }
    }

    public function updateQuantity($cartId, $action)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->find($cartId);

        if (!$cartItem) return;

        if ($action == 'increase') {
            $cartItem->quantity++;
        } elseif ($action == 'decrease' && $cartItem->quantity > 1) {
            $cartItem->quantity--;
        }

        $cartItem->save();
        $this->loadCart();
    }

    public function removeItem($cartId)
    {
        CartItem::where('user_id', Auth::id())->where('id', $cartId)->delete();
        $this->loadCart();
    }
}
