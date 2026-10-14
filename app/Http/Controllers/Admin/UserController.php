<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::withCount('orders')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['orders' => function ($q) {
            $q->latest()->take(10);
        }]);

        $totalSpent = $user->orders()->where('order_status', 'delivered')->sum('grand_total');

        return view('admin.users.show', compact('user', 'totalSpent'));
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        // Prevent admin from deactivating themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own administrative account.');
        }

        // Prevent deactivating the last active admin
        if ($user->isAdmin() && $user->is_active) {
            $activeAdminCount = User::where('role', 'admin')->where('is_active', true)->count();
            if ($activeAdminCount <= 1) {
                return back()->with('error', 'Cannot deactivate the sole remaining active administrator.');
            }
        }

        $user->update(['is_active' => ! $user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User account for {$user->name} has been {$status}.");
    }
}
