<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the contact page with authentic Vrindavan store details and WhatsApp order assistance.
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
            ['name' => 'Sampark Karein', 'url' => route('contact')],
        ];

        return view('contact', compact('categories', 'breadcrumbs'));
    }
}
