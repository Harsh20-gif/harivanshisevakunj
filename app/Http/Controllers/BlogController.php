<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display the Braj Katha & Devotional Wisdom page.
     */
    public function index(): View
    {
        $categories = Category::active()
            ->with('media')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Braj Katha', 'url' => route('blog')],
        ];

        return view('blog', compact('categories', 'breadcrumbs'));
    }
}
