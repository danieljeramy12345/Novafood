@extends('layouts.admin')

@section('title', 'Registered Accounts')
@section('page_title', 'Users & Customers')
@section('page_subtitle', 'View registered customer accounts, order frequency, and account status.')

@section('content')
    <div class="space-y-6">
        <!-- Search and Filter -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search user by name, email, or phone..." 
                    class="bg-slate-900 border border-purple-800/60 rounded-xl px-4 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 w-full sm:w-72"
                />

                <select 
                    name="role" 
                    onchange="this.form.submit()" 
                    class="bg-slate-900 border border-purple-800/60 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-orange-500 cursor-pointer"
                >
                    <option value="">All Roles</option>
                    <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customers</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrators</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-purple-700 hover:bg-purple-600 text-white font-bold text-xs uppercase tracking-wider">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-slate-400 hover:text-white">Reset</a>
                @endif
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-slate-900 border border-purple-900/40 rounded-3xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="text-slate-400 uppercase tracking-wider border-b border-purple-900/40 bg-slate-950/40">
                        <tr>
                            <th class="py-3.5 px-4">User</th>
                            <th class="py-3.5 px-4">Contact</th>
                            <th class="py-3.5 px-4">Role</th>
                            <th class="py-3.5 px-4">Orders Placed</th>
                            <th class="py-3.5 px-4">Account Status</th>
                            <th class="py-3.5 px-4">Registered Date</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-900/20">
                        @forelse($users as $user)
                            <tr class="hover:bg-purple-950/20 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-white text-sm">{{ $user->name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $user->email }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-300">
                                    {{ $user->phone ?? 'Not provided' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->isAdmin() ? 'bg-amber-950 text-amber-300 border border-amber-800' : 'bg-purple-950 text-purple-300 border border-purple-800' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-white">
                                    {{ $user->orders_count }} orders
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $user->is_active ? 'bg-emerald-950 text-emerald-300 border-emerald-800' : 'bg-rose-950 text-rose-300 border-rose-800' }}">
                                        {{ $user->is_active ? 'Active' : 'Deactivated' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-400">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="p-2 rounded-lg bg-slate-800 hover:bg-purple-900 text-slate-300 hover:text-white transition-colors" title="View Details">
                                            👁️
                                        </a>

                                        @if($user->id !== Auth::id())
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" onsubmit="return confirm('Toggle active status for this user account?');">
                                                @csrf
                                                <button type="submit" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors text-xs font-bold" title="Toggle Active">
                                                    {{ $user->is_active ? '🚫' : '✓' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    No accounts match the criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-purple-900/30">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
