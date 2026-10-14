<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FoodController as AdminFoodController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Food menu and details
Route::get('/menu', [FoodController::class, 'index'])->name('menu');
Route::get('/menu/{food:slug}', [FoodController::class, 'show'])->name('foods.show');

// Shopping Cart (available to guests and customers)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/items/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('/items/{item}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Customer Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Customer Portal
    Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/my-orders', [CustomerDashboardController::class, 'orders'])->name('customer.orders');
    Route::get('/orders/{order}', [CustomerDashboardController::class, 'orderShow'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [CustomerDashboardController::class, 'cancelOrder'])->name('orders.cancel');
    Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('customer.profile');
    Route::post('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('customer.profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Foods Management
    Route::resource('foods', AdminFoodController::class);
    Route::post('/foods/{food}/toggle', [AdminFoodController::class, 'toggleAvailability'])->name('foods.toggle');

    // Categories Management
    Route::resource('categories', AdminCategoryController::class);

    // Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('users.toggle');
});
