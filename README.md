# Harivanshi Seva Kunj — Production SEO & Deployment Guide

## Production Domain & Canonical URL Strategy

The project is currently configured for local development (`http://127.0.0.1:8000`). No production domain has been purchased yet.

### Step 1: When the production domain is purchased
Set `CANONICAL_URL` in your `.env` file to the purchased domain:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.harivanshisevakunj.com
CANONICAL_URL=https://www.harivanshisevakunj.com
```

- When `CANONICAL_URL` is set, all canonical link tags, Open Graph URLs, Twitter cards, XML sitemaps, robots.txt, and JSON-LD structured data automatically use this production domain.
- When `CANONICAL_URL` is empty (in local dev), the site automatically falls back to `APP_URL` (`http://localhost:8000`). Localhost URLs will **never** leak into production once `CANONICAL_URL` is configured.
- No HTTPS redirects are forced on `localhost`; in production, configure TLS/HTTPS on your web server (Nginx/Cloudflare/Caddy).

---

## Vrindavan Local Business Information Required from Owner

The business operates online and has a physical shop in Vrindavan Dham. To prevent fabricating false data, the physical street address and coordinates are left unset until confirmed by the business owner.

Update these keys in `.env` once confirmed:
```env
# Physical shop address in Vrindavan (required to emit Schema.org LocalBusiness / Store schema):
BUSINESS_STREET_ADDRESS="Shop No. XX, Raman Reti Road, Near ..."
BUSINESS_POSTAL_CODE="281121"
BUSINESS_LATITUDE="27.5800"
BUSINESS_LONGITUDE="77.7000"
BUSINESS_OPENING_HOURS="Mo-Su 08:00-21:00"

# Contact details (already pre-configured with genuine numbers):
BUSINESS_PHONE="+91 92860 70355"
WHATSAPP_NUMBER="919286070355"
BUSINESS_EMAIL="harivanshisevakunj@gmail.com"
```
> **Note on Schema.org:** LocalBusiness / Store structured data is strictly gated and will only be emitted once `BUSINESS_STREET_ADDRESS` is provided.

---

## Technical SEO Architecture Implemented

1. **Dynamic Metadata (`<x-seo-meta>`):**
   - Single `<title>` and `<meta name="description">` on every page.
   - Dynamic canonical `<link rel="canonical">` stripping tracking parameters.
   - Smart robots meta: `index, follow` on public pages; `noindex, follow` on cart and internal search results (`?q=...`); `noindex, nofollow` on admin.
   - Complete Open Graph and Twitter Card tags.

2. **Crawlable Ecommerce Structure:**
   - Public product detail pages at `/products/{slug}` with real DB pricing, specs, images, and WhatsApp/cart ordering.
   - Category pages and product cards link using standard HTML `<a href>` tags.
   - Breadcrumb navigation on shop and product pages with Schema.org `BreadcrumbList`.
   - Unpublished/draft products return 404 and are excluded from listings and sitemaps.

3. **Dynamic XML Sitemap (`/sitemap.xml`):**
   - Live endpoint at `/sitemap.xml` listing homepage, catalogue, active categories with products, published products, and informational pages.
   - Fully dynamic priorities, change frequencies, and RFC 3339 `<lastmod>` timestamps based on database `updated_at`.

4. **Dynamic Robots.txt (`/robots.txt`):**
   - Live endpoint at `/robots.txt` disallowing `/admin/` and `/cart`, allowing public pages, and referencing `Sitemap: {CANONICAL_URL}/sitemap.xml`.

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
