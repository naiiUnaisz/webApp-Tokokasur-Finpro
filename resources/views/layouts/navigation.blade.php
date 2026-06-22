
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">


<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center px-6">
                    <img
                    src="{{ asset('Frontend/landingPage_TokoKasur/img/logo_buscil.png') }}"
                    alt="logo"
                    class="h-7 w-auto object-contain"
                  />
                </div>
                <div class="shrink-0 flex items-center">
                    <x-nav-link :href=" route(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex overflow-x-auto no-scrollbar">
                    @if (auth()->user()->role == 'admin')
                        
                    <x-nav-link :href="route('admin.categories')" :active="request()->routeIs('admin.categories')">
                        {{ __('Kelola Kategori') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('admin.brands')" :active="request()->routeIs('admin.brands')">
                        {{ __('Kelola Merek') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('admin.foam-types')" :active="request()->routeIs('admin.foam-types')">
                        {{ __('Kelola Jenis Busa') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('admin.sizes')" :active="request()->routeIs('admin.sizes')">
                        {{ __('Kelola Ukuran') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.products')" :active="request()->routeIs('admin.products')">
                        {{ __('Kelola Produk') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.imageDashboard')" :active="request()->routeIs('admin.imageDashboard')">
                        {{ __('Kelola Image') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                        {{ __('Kelola User') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.usersAddress')" :active="request()->routeIs('admin.usersAddress')">
                        {{ __('Kelola Alamat') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders')">
                        {{ __('Kelola Order') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.orderItems')" :active="request()->routeIs('admin.orderItems')">
                        {{ __('Kelola Order Item') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.payments')" :active="request()->routeIs('admin.payments')">
                        {{ __('Kelola pembayaran') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.reviews')" :active="request()->routeIs('admin.reviews')">
                        {{ __('Kelola Review') }}
                    </x-nav-link>
                    @endif
                  
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('landingpage')">
                            {{ __('Landing Page') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (auth()->user()->role == 'admin')
                
            <x-responsive-nav-link :href="route('admin.categories')" :active="request()->routeIs('admin.categories')">
                {{ __('Kelola Kategori') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.brands')" :active="request()->routeIs('admin.brands')">
                {{ __('Kelola Merek') }}
            </x-responsive-nav-link>
    
            <x-responsive-nav-link :href="route('admin.foam-types')" :active="request()->routeIs('admin.foam-types')">
                {{ __('Kelola Jenis Busa') }}
            </x-responsive-nav-link>
    
            <x-responsive-nav-link :href="route('admin.sizes')" :active="request()->routeIs('admin.sizes')">
                {{ __('Kelola Ukuran') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.products')" :active="request()->routeIs('admin.products')">
                {{ __('Kelola Produk') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link  :href="route('admin.imageDashboard')" :active="request()->routeIs('admin.imageDashboard')">
                {{ __('Kelola Image') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link  :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                {{ __('Kelola User') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.usersAddress')" :active="request()->routeIs('admin.usersAddress')">
                {{ __('Kelola Alamat') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link  :href="route('admin.orders')" :active="request()->routeIs('admin.orders')">
                {{ __('Kelola Order') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link  :href="route('admin.orderItems')" :active="request()->routeIs('admin.orderItems')">
                {{ __('Kelola Order Items') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link  :href="route('admin.payments')" :active="request()->routeIs('admin.payments')">
                {{ __('Kelola Pembayaran') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link  :href="route('admin.reviews')" :active="request()->routeIs('admin.reviews')">
                {{ __('Kelola Review') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('landingpage')">
                    {{ __('Landing Page') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
