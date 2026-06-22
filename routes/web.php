<?php

use App\Models\Order;
use App\Livewire\Admin\ManageImage;
use App\Livewire\Admin\ManageSizes;
use App\Livewire\Admin\ManageBrands;
use App\Livewire\Admin\ManageVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ManageKategori;
use App\Livewire\Admin\ManageProducts;
use App\Livewire\Admin\ManageJenisBusa;
use App\Livewire\Admin\Management\User;
use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\Management\ReviewManagement;
use App\Livewire\Admin\Shop\OrderManagement;
use App\Livewire\Admin\ProductImageDashboard;
use App\Livewire\Admin\Management\UserAddress;
use App\Livewire\Admin\Shop\OrderItemManagement;
use App\Livewire\Admin\Shop\PaymentManagement;
use App\Livewire\Front\Checkout;
use App\Livewire\Front\DetailProduct;
use App\Livewire\Front\Katalog;
use App\Livewire\Front\Keranjang;
use App\Livewire\Front\LandingPage;
use App\Livewire\Front\Payment;
use App\Livewire\Front\Wislist;
use App\Models\Wishlist;

use function Termwind\render;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return redirect()->route('landingpage');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/kategori', ManageKategori::class)->name('categories');
    Route::get('/brands', ManageBrands::class)->name('brands');
    Route::get('/FoamType', ManageJenisBusa::class)->name('foam-types');
    Route::get('/Size', ManageSizes::class)->name('sizes');
    Route::get('/produk', ManageProducts::class)->name('products');
    Route::get('/images/dashboard', ProductImageDashboard::class)->name('imageDashboard');
    Route::get('/manage-image/{productId}', ManageImage::class)->name('images');
    Route::get('/users', User::class)->name('users');
    Route::get('/user-address', UserAddress::class)->name('usersAddress');
    Route::get('/order-management', OrderManagement::class)->name('orders');
    Route::get('/order-items', OrderItemManagement::class)->name('orderItems');
    Route::get('/Payment-confirm', PaymentManagement::class)->name('payments');
    Route::get('/Product-reviews', ReviewManagement::class)->name('reviews');
});

// Route frontend
Route::get('/', LandingPage::class)->name('landingpage');
Route::get('/katalog', Katalog::class)->name('User.katalog');
Route::get('/detail-product/{product}', DetailProduct::class)->name('User.detailProduct');
Route::get('/Cart-Shopping', Keranjang::class)->name('User.CartShopping');
Route::get('/Cart-Payment', Payment::class)->name('User.CartPayment');
Route::get('/Cart-Wishlist', Wislist::class)->name('User.CartWishlist');
Route::get('/Status-Pembayaran', Checkout::class)->name('User.Checkout');




require __DIR__.'/auth.php';
