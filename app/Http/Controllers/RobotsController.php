<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    /**
     * Render dynamic robots.txt pointing to the canonical sitemap.
     */
    public function index(): Response
    {
        $sitemapUrl = Seo::url('sitemap.xml');

        $content = "User-agent: *\n"
            . "Disallow: /admin/\n"
            . "Disallow: /admin\n"
            . "Disallow: /cart\n"
            . "Disallow: /cart/\n"
            . "Allow: /\n\n"
            . "Sitemap: {$sitemapUrl}\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}
