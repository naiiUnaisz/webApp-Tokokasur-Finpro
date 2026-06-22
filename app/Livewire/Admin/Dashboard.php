<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.admin')]

class Dashboard extends Component
{
    public $totalUsers;
    public $totalCustomers;
    public $totalProducts;
    public $totalOrders;
    public $pendingOrders;
    public $totalIncome;
    public $recentOrders;
    public $pendingReviews;

    public function mount()
    {
        $this->totalUsers = User::count();
        $this->totalCustomers = User::where('role', 'customer')->count();
        $this->totalProducts = Product::count();
        $this->totalOrders = Order::count();
        $this->pendingOrders = Order::where('status', 'pending')->count();
        $this->totalIncome = Order::where('status', 'completed')
            ->sum('total_amount');
        $this->pendingReviews = Review::where('is_approved', false)->count();
        $this->recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        $ordersPerMonth = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $incomePerMonth = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_amount) as total')
        )
        ->where('status', 'completed')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        return view('livewire.admin.dashboard', compact('ordersPerMonth', 'incomePerMonth'));
    }
}
