@extends('layouts.admin')

@section('title', "Customer Account: {$user->name}")
@section('page_title', "User Account: {$user->name}")
@section('page_subtitle', "Inspect customer details, total spending, and past food orders.")

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-xs text-purple-400 hover:text-white transition-colors">
            ← Back to Users
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Customer Info Card (4 cols) -->
        <div class="lg:col-span-4 bg-slate-900 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
            <div class="flex items-center gap-4 border-b border-purple-900/30 pb-6">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center text-white font-black text-2xl uppercase shadow-lg">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-base font-bold text-white">{{ $user->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->isAdmin() ? 'bg-amber-950 text-amber-300 border border-amber-800' : 'bg-purple-950 text-purple-300 border border-purple-800' }}">
                        {{ $user->role }}
                    </span>
                </div>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Email Address</span>
                    <span class="text-white font-semibold">{{ $user->email }}</span>
                </div>

                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Contact Phone</span>
                    <span class="text-white">{{ $user->phone ?? 'Not specified' }}</span>
                </div>

                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Account Status</span>
                    <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $user->is_active ? 'bg-emerald-950 text-emerald-300 border-emerald-800' : 'bg-rose-950 text-rose-300 border-rose-800' }}">
                        {{ $user->is_active ? 'Active & Verified' : 'Deactivated' }}
                    </span>
                </div>

                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Default Delivery Address</span>
                    <p class="text-slate-300 leading-relaxed bg-slate-950 p-3 rounded-xl border border-purple-950 mt-1">
                        {{ $user->address ?? 'No default address saved.' }}
                    </p>
                </div>

                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Member Since</span>
                    <span class="text-slate-300">{{ $user->created_at->format('F d, Y') }}</span>
                </div>
            </div>

            <div class="pt-4 border-t border-purple-900/30">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Delivered Food Spend</span>
                <span class="text-2xl font-black text-orange-400">₱{{ number_format($totalSpent, 2) }}</span>
            </div>
        </div>

        <!-- Order History (8 cols) -->
        <div class="lg:col-span-8 bg-slate-900 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl">
            <h3 class="text-base font-bold text-white mb-6 border-b border-purple-900/30 pb-3">Recent Orders by this Customer</h3>

            @if($user->orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="text-slate-400 uppercase tracking-wider border-b border-purple-900/40">
                            <tr>
                                <th class="py-3 px-3">Order #</th>
                                <th class="py-3 px-3">Date</th>
                                <th class="py-3 px-3">Total</th>
                                <th class="py-3 px-3">Status</th>
                                <th class="py-3 px-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-purple-900/20">
                            @foreach($user->orders as $order)
                                <tr class="hover:bg-purple-950/20 transition-colors">
                                    <td class="py-3 px-3 font-bold text-white">#{{ $order->order_number }}</td>
                                    <td class="py-3 px-3 text-slate-400">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="py-3 px-3 font-black text-orange-400">₱{{ number_format((float) $order->grand_total, 2) }}</td>
                                    <td class="py-3 px-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $order->status_badge_class }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-purple-900 text-slate-200 font-semibold text-xs transition-colors">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-slate-400 py-6 text-center">This customer has not placed any orders yet.</p>
            @endif
        </div>
    </div>
@endsection
