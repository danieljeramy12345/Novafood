@extends('layouts.app')

@section('title', 'My Profile Settings — NovaFood')

@section('content')
    <div class="max-w-2xl mx-auto my-8">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-white tracking-tight">Profile Settings</h1>
            <p class="text-sm text-slate-400 mt-1">Manage your customer contact details and login credentials.</p>
        </div>

        <div class="bg-slate-900/80 border border-purple-900/40 rounded-3xl p-6 sm:p-10 shadow-xl space-y-8">
            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-purple-400 border-b border-purple-900/30 pb-2">Personal Information</h3>

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Full Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $user->name) }}" 
                            required 
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
                                required 
                                class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                            />
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Phone Number</label>
                            <input 
                                type="text" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone', $user->phone) }}" 
                                required 
                                class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Default Delivery Address</label>
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="3" 
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 resize-none"
                        >{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>

                <!-- Password Update Section -->
                <div class="space-y-4 pt-4 border-t border-purple-900/30">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-purple-400 border-b border-purple-900/30 pb-2">Change Password (Leave blank to keep current)</h3>

                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Current Password</label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            placeholder="Required only when changing password"
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="new_password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">New Password</label>
                            <input 
                                type="password" 
                                id="new_password" 
                                name="new_password" 
                                placeholder="Min. 8 characters"
                                class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                            />
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Confirm New Password</label>
                            <input 
                                type="password" 
                                id="new_password_confirmation" 
                                name="new_password_confirmation" 
                                placeholder="Re-enter new password"
                                class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                            />
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button 
                        type="submit" 
                        class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-purple-600 to-orange-500 hover:from-purple-500 hover:to-orange-400 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-purple-600/30 transition-all duration-200"
                    >
                        Save Profile Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
