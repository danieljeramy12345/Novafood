@extends('layouts.app')

@section('title', 'Sign In — NovaFood')

@section('content')
    <div class="max-w-md mx-auto my-12">
        <div class="bg-slate-900/80 border border-purple-900/50 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
            <!-- Glow background -->
            <div class="absolute -top-12 -right-12 w-40 h-40 bg-purple-600/20 blur-[60px] rounded-full pointer-events-none"></div>

            <div class="text-center mb-8 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-purple-600 to-orange-500 flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg shadow-purple-600/30">
                    🍲
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight">Welcome Back</h1>
                <p class="text-xs text-slate-400 mt-1">Sign in to your NovaFood account to view orders and checkout faster.</p>
            </div>

            <form action="{{ route('login.process') }}" method="POST" class="space-y-5 relative z-10">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        placeholder="you@example.com"
                        class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                    />
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Password</label>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="••••••••"
                        class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                    />
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                        <input type="checkbox" name="remember" class="rounded bg-slate-950 border-purple-800 text-orange-500 focus:ring-orange-500">
                        <span>Remember me</span>
                    </label>
                </div>

                <button 
                    type="submit" 
                    class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-purple-600 to-orange-500 hover:from-purple-500 hover:to-orange-400 text-white font-bold text-sm uppercase tracking-wider shadow-lg shadow-purple-600/30 hover:shadow-orange-500/30 transition-all duration-200"
                >
                    Sign In to NovaFood
                </button>
            </form>

            <!-- Test Credentials Helper -->
            <div class="mt-8 pt-6 border-t border-purple-900/30 text-xs text-slate-400 space-y-2">
                <span class="font-bold text-purple-300 block">Quick Demo Credentials:</span>
                <div class="p-2.5 rounded-xl bg-slate-950/80 border border-purple-950 flex justify-between items-center text-[11px]">
                    <div>
                        <span class="text-orange-400 font-bold">Admin:</span> admin@novafood.com / Admin12345!
                    </div>
                    <button type="button" onclick="document.getElementById('email').value='admin@novafood.com'; document.getElementById('password').value='Admin12345!';" class="text-purple-300 hover:text-white underline">Fill</button>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-950/80 border border-purple-950 flex justify-between items-center text-[11px]">
                    <div>
                        <span class="text-purple-400 font-bold">Customer:</span> customer@novafood.com / Customer123!
                    </div>
                    <button type="button" onclick="document.getElementById('email').value='customer@novafood.com'; document.getElementById('password').value='Customer123!';" class="text-purple-300 hover:text-white underline">Fill</button>
                </div>
            </div>

            <div class="text-center mt-6 text-xs text-slate-400">
                Don't have an account yet? 
                <a href="{{ route('register') }}" class="font-bold text-orange-400 hover:underline">Create an account</a>
            </div>
        </div>
    </div>
@endsection
