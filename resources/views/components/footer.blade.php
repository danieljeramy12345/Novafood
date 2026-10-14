<footer class="bg-slate-950 border-t border-purple-950 text-slate-400 text-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <!-- Brand Column -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-purple-600 to-orange-500 flex items-center justify-center shadow-lg shadow-purple-600/30">
                        <span class="text-xl">🍲</span>
                    </div>
                    <span class="text-2xl font-black tracking-tight text-white">
                        Nova<span class="text-orange-400">Food</span>
                    </span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Satisfying cravings with blazing fast delivery across Metro Manila and surrounding cities. Freshly prepared, securely packed, and delivered to your doorstep.
                </p>
                <div class="flex items-center gap-2 text-xs text-orange-400 font-semibold">
                    <span>⚡ Standard Delivery: 30-45 mins</span>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Quick Links</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('home') }}" class="hover:text-orange-400 transition-colors">Home</a></li>
                    <li><a href="{{ route('menu') }}" class="hover:text-orange-400 transition-colors">Browse Food Menu</a></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:text-orange-400 transition-colors">View Cart</a></li>
                    @auth
                        <li><a href="{{ route('customer.orders') }}" class="hover:text-orange-400 transition-colors">Order History</a></li>
                        <li><a href="{{ route('customer.dashboard') }}" class="hover:text-orange-400 transition-colors">Customer Portal</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-orange-400 transition-colors">Customer Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-orange-400 transition-colors">Create Account</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Food Categories -->
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Popular Cuisines</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('menu', ['category' => 'burgers']) }}" class="hover:text-orange-400 transition-colors">Flame-Grilled Burgers</a></li>
                    <li><a href="{{ route('menu', ['category' => 'pizza']) }}" class="hover:text-orange-400 transition-colors">Stone-Baked Pizza</a></li>
                    <li><a href="{{ route('menu', ['category' => 'chicken']) }}" class="hover:text-orange-400 transition-colors">Crispy Fried Chicken</a></li>
                    <li><a href="{{ route('menu', ['category' => 'rice-meals']) }}" class="hover:text-orange-400 transition-colors">Filipino Rice Meals</a></li>
                    <li><a href="{{ route('menu', ['category' => 'desserts']) }}" class="hover:text-orange-400 transition-colors">Sweet Treats & Cakes</a></li>
                </ul>
            </div>

            <!-- Contact & Hours -->
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Contact & Support</h4>
                <ul class="space-y-3 text-xs">
                    <li class="flex items-start gap-2">
                        <span class="text-purple-400">📍</span>
                        <span>Bonifacio Global City, Taguig, Philippines</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-purple-400">📞</span>
                        <span>+63 (02) 8888-NOVA (6682)</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-purple-400">✉️</span>
                        <span>support@novafood.ph</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-purple-400">⏰</span>
                        <span>Mon - Sun: 8:00 AM - 11:00 PM</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-purple-950/60 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <p>&copy; {{ date('Y') }} NovaFood Delivery Management System. All rights reserved. Prices in Philippine Peso (₱).</p>
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-950/80 border border-purple-800/40 text-purple-300 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Order Line Active
                </span>
            </div>
        </div>
    </div>
</footer>
