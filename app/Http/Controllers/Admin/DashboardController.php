<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin panel dashboard.
     */
    public function index(): View
    {
        // Inspect existing models and database tables dynamically
        $hasProducts   = Schema::hasTable('products');
        $hasCategories = Schema::hasTable('categories');
        $hasOrders     = Schema::hasTable('orders');

        $totalProducts   = $hasProducts ? DB::table('products')->count() : null;
        $totalCategories = $hasCategories ? DB::table('categories')->count() : null;
        $totalOrders     = $hasOrders ? DB::table('orders')->count() : null;

        $pendingOrders = null;
        if ($hasOrders) {
            if (Schema::hasColumn('orders', 'status')) {
                $pendingOrders = DB::table('orders')->where('status', 'pending')->count();
            } else {
                $pendingOrders = 0;
            }
        }

        $recentOrders = $hasOrders
            ? DB::table('orders')->latest()->take(5)->get()
            : collect();

        // System overview statistics
        $stats = [
            'products' => [
                'label'       => 'Total Products',
                'value'       => $totalProducts,
                'available'   => $hasProducts,
                'icon'        => 'cube',
                'description' => 'Catalog inventory items',
                'module'      => 'Products CRUD (Sprint 2 - Task 3)',
            ],
            'categories' => [
                'label'       => 'Total Categories',
                'value'       => $totalCategories,
                'available'   => $hasCategories,
                'icon'        => 'tag',
                'description' => 'Product classification groups',
                'module'      => 'Categories CRUD (Sprint 2 - Task 4)',
            ],
            'orders' => [
                'label'       => 'Total Orders',
                'value'       => $totalOrders,
                'available'   => $hasOrders,
                'icon'        => 'shopping-bag',
                'description' => 'Lifetime placed orders',
                'module'      => 'Order Management (Sprint 2 - Task 5)',
            ],
            'pending_orders' => [
                'label'       => 'Pending Orders',
                'value'       => $pendingOrders,
                'available'   => $hasOrders,
                'icon'        => 'clock',
                'description' => 'Awaiting fulfillment/processing',
                'module'      => 'Order Management (Sprint 2 - Task 5)',
            ],
        ];

        // Quick navigation shortcuts
        $quickLinks = [
            [
                'title'       => 'Media Library',
                'description' => 'Upload, organize and manage devotional imagery and product banners',
                'icon'        => 'photo',
                'route'       => route('admin.media.index'),
                'badge'       => 'Active',
                'is_ready'    => true,
            ],
            [
                'title'       => 'Products',
                'description' => 'Manage sacred malas, dress collections, pricing and inventory',
                'icon'        => 'cube',
                'route'       => route('admin.products.index'),
                'badge'       => 'Active',
                'is_ready'    => true,
            ],
            [
                'title'       => 'Categories',
                'description' => 'Configure storefront collection categories and visual badges',
                'icon'        => 'tag',
                'route'       => route('admin.categories.index'),
                'badge'       => 'Active',
                'is_ready'    => true,
            ],
            [
                'title'       => 'Orders',
                'description' => 'View incoming devotee orders, shipping slips and WhatsApp status',
                'icon'        => 'shopping-bag',
                'route'       => null,
                'badge'       => $hasOrders ? 'Active' : 'Upcoming (Sprint 2 - Task 5)',
                'is_ready'    => $hasOrders,
            ],
        ];

        return view('admin.dashboard', compact('stats', 'recentOrders', 'quickLinks', 'hasOrders'));
    }
}
