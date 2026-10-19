<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Display the About Us page with active categories.
     */
    public function index(): View
    {
        $categories = Category::active()
            ->with('media')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('about', compact('categories'));
    }
}
