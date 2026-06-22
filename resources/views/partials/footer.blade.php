<div>
      <!--  FOOTER -->
      <footer class="bg-[#1B1717] text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                <!-- Logo & Deskripsi -->
              <div class="flex items-center justify-center
                w-28 sm:w-32 md:w-40
                rounded-lg p-2 shadow-md"
                 style="background-color: rgb(239, 218, 218)">
                <img
                    src="{{ asset('Frontend/landingPage_TokoKasur/img/logo_buscil.png') }}"
                    alt="logo"
                    class="h-6 sm:h-7 md:h-8 w-auto object-contain"
                />
              </div>

                      <br>
                    <p class="text-sm text-gray-400">Spesialis kasur kualitas terbaik dengan berbagai varian ukuran dan busa</p>
                </div>
                <!-- Produk & Akun -->
                <div>
                    <h3 class="font-semibold text-base mb-4">Akun Saya</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{route('landingpage')}}" class="hover:text-primary-custom transition duration-150">Home</a></li>
                        <li><a href="{{route('User.katalog')}}" class="hover:text-primary-custom transition duration-150">Katalog</a></li>
                        <li><a href="{{route('User.Checkout')}}" class="hover:text-primary-custom transition duration-150">Shopping</a></li>
                        <li><a href="{{route('User.CartWishlist')}}" class="hover:text-primary-custom transition duration-150">Wishlist</a></li>
                        <li><a href="{{route('User.CartShopping')}}" class="hover:text-primary-custom transition duration-150">Keranjang</a></li>
                    </ul>
                </div>
                <!--  Kontak & Sosial  -->
                <div>
                    <h3 class="font-semibold text-base mb-4">Hubungi Kami</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="https://www.instagram.com/busa_cileungsi/" target="_blank" class="hover:text-primary-custom transition duration-150">Instagram : @busa_cileungsi </a></li>
                        <li><a href="https://wa.me/6283890909067" target="_blank" class="hover:text-primary-custom transition duration-150">Telepon: +62 838 9090 9067</a></li>
                    </ul>
                    <div class="flex space-x-4 mt-4">
                        <i data-lucide="facebook" class="w-6 h-6 hover:text-primary-custom cursor-pointer"></i>
                        <i data-lucide="instagram" class="w-6 h-6 hover:text-primary-custom cursor-pointer"></i>
                        <i data-lucide="twitter" class="w-6 h-6 hover:text-primary-custom cursor-pointer"></i>
                    </div>
                </div>
            </div>
            <div class="mt-10 pt-6 border-t border-gray-700 text-center text-sm text-gray-400">
                &copy; 2024 KasurBusaCileungsi. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>
    
    <!-- FLOATING WHATSAPP CHAT -->
    <a href="https://wa.me/6283890909067?text=Halo%2C%20saya%20ingin%20bertanya%20tentang%20produk%20Anda"
   target="_blank"
   class="whatsapp-float fixed bottom-6 right-6 p-4 rounded-full shadow-xl text-white z-50">
   <i class="fa-brands fa-whatsapp"></i>
   <span class="font-semibold hidden md:inline">Chat via WhatsApp</span>
</a>

</div>