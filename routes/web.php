<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes — Harivanshi Seva Kunj
|--------------------------------------------------------------------------
| Routes are added incrementally as features are built.
| Stub routes for about, contact, products, and cart are defined here
| to prevent broken links during development.
*/

// ── Home ─────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Storefront Products Catalogue & Detail ────────────────────
Route::get('/products', [\App\Http\Controllers\StorefrontProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [\App\Http\Controllers\StorefrontProductController::class, 'show'])->name('products.show');

// ── Technical SEO: Dynamic Sitemap & Robots.txt ──────────────
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [\App\Http\Controllers\RobotsController::class, 'index'])->name('robots');

// ── Storefront About Page ─────────────────────────────────────
Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');

// ── Storefront Cart ───────────────────────────────────────────
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [\App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{productId}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{productId}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');

// ── Storefront Contact Page ───────────────────────────────────
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact');

// ── Storefront Braj Katha & Devotional Wisdom ─────────────────
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog');

// ── Admin Panel Routes ───────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Guest Routes (Login)
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.submit');

    // Admin Logout
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes (Requires authentication AND is_admin privilege)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Central Media Library
        Route::get('/media', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
        Route::post('/media', [\App\Http\Controllers\Admin\MediaController::class, 'store'])->name('media.store');
        Route::get('/media/{media}', [\App\Http\Controllers\Admin\MediaController::class, 'show'])->name('media.show');
        Route::patch('/media/{media}', [\App\Http\Controllers\Admin\MediaController::class, 'update'])->name('media.update');
        Route::delete('/media/{media}', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy');

        // Categories Management
        Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::patch('/categories/{category}/toggle', [\App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle');

        // Products Management
        Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
        Route::patch('/products/{product}/toggle', [\App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggle');
    });
});
