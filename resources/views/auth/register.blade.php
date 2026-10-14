@extends('layouts.app')

@section('title', 'Create Account — NovaFood')

@section('content')
    <div class="max-w-lg mx-auto my-10">
        <div class="bg-slate-900/80 border border-purple-900/50 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
            <!-- Glow background -->
            <div class="absolute -top-12 -left-12 w-40 h-40 bg-orange-600/15 blur-[60px] rounded-full pointer-events-none"></div>

            <div class="text-center mb-8 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-purple-600 to-orange-500 flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg shadow-purple-600/30">
                    🍲
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight">Create NovaFood Account</h1>
                <p class="text-xs text-slate-400 mt-1">Join NovaFood to track orders, save delivery locations, and get exclusive food promos.</p>
            </div>

            <form action="{{ route('register.process') }}" method="POST" class="space-y-4 relative z-10">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Full Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus
                        placeholder="Juan Dela Cruz"
                        class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Email Address *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            placeholder="juan@example.com"
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                        />
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Phone Number *</label>
                        <input 
                            type="text" 
                            id="phone" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            required 
                            placeholder="+63 917 123 4567"
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                        />
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Default Delivery Address (Optional)</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address" 
                        value="{{ old('address') }}" 
                        placeholder="Street, Barangay, City, Metro Manila"
                        class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Password *</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            placeholder="Min. 8 characters"
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                        />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Confirm Password *</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required 
                            placeholder="Re-enter password"
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                        />
                    </div>
                </div>

                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-purple-600 to-orange-500 hover:from-purple-500 hover:to-orange-400 text-white font-bold text-sm uppercase tracking-wider shadow-lg shadow-purple-600/30 hover:shadow-orange-500/30 transition-all duration-200"
                    >
                        Create Customer Account
                    </button>
                </div>
            </form>

            <div class="text-center mt-6 text-xs text-slate-400">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-bold text-orange-400 hover:underline">Sign In</a>
            </div>
        </div>
    </div>
@endsection
