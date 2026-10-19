<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Str;

class WhatsApp
{
    /**
     * Get the configured, validated business WhatsApp number in E.164-compatible format (digits only).
     */
    public static function getBusinessNumber(): ?string
    {
        $number = config('services.whatsapp.number')
            ?: (config('seo.business.whatsapp') ?: (env('WHATSAPP_NUMBER') ?: '919286070355'));

        return self::formatPhoneNumber($number);
    }

    /**
     * Format and validate a phone number for wa.me links.
     * Strips all non-digit characters and ensures appropriate length.
     * If given a 10-digit Indian mobile number, prepends country code 91.
     */
    public static function formatPhoneNumber(?string $number): ?string
    {
        if (empty($number)) {
            return null;
        }

        $clean = preg_replace('/[^0-9]/', '', (string) $number);

        if (empty($clean)) {
            return null;
        }

        // If 10 digits without country code, prepend India country code 91
        if (strlen($clean) === 10) {
            $clean = '91' . $clean;
        }

        // International E.164 phone numbers typically have 10-15 digits
        if (strlen($clean) < 10 || strlen($clean) > 15) {
            return null;
        }

        return $clean;
    }

    /**
     * Check if a valid WhatsApp business number is configured.
     */
    public static function isValidNumber(?string $number = null): bool
    {
        return !empty($number ? self::formatPhoneNumber($number) : self::getBusinessNumber());
    }

    /**
     * Resolve an absolute, publicly accessible image URL for WhatsApp enquiries.
     * Guarantees:
     *  1. Re-bases onto canonical domain when CANONICAL_URL is configured in production.
     *  2. Never exposes localhost or private filesystem paths in production messages.
     *  3. Returns null gracefully if no image is present.
     */
    public static function resolveAbsoluteImageUrl(?string $image): ?string
    {
        if (empty($image)) {
            return null;
        }

        $image = trim($image);
        if ($image === '') {
            return null;
        }

        $canonicalBase = config('seo.canonical_url');
        if (empty($canonicalBase)) {
            $canonicalBase = config('app.url');
        }
        if (empty($canonicalBase)) {
            $canonicalBase = url('/');
        }
        $canonicalBase = rtrim((string) $canonicalBase, '/');

        // If already an absolute URL (starts with http:// or https://)
        if (preg_match('#^https?://#i', $image)) {
            $imageParts = parse_url($image);
            $imageHost = $imageParts['host'] ?? '';

            $canonicalParts = parse_url($canonicalBase);
            $canonicalHost = $canonicalParts['host'] ?? '';

            $isLocalImageHost = in_array($imageHost, ['localhost', '127.0.0.1', '::1'], true);
            $isPublicCanonicalHost = !empty($canonicalHost) && !in_array($canonicalHost, ['localhost', '127.0.0.1', '::1'], true);

            // In production with a public canonical domain, prevent leaking localhost
            if ($isLocalImageHost && $isPublicCanonicalHost) {
                $path = $imageParts['path'] ?? '';
                $query = isset($imageParts['query']) ? '?' . $imageParts['query'] : '';
                return $canonicalBase . $path . $query;
            }

            return $image;
        }

        // If it's a relative path (e.g. '/storage/...', 'storage/...', 'images/...')
        $cleanPath = '/' . ltrim($image, '/');
        return $canonicalBase . $cleanPath;
    }

    /**
     * Resolve an absolute canonical URL for a product detail page.
     */
    public static function resolveAbsoluteProductUrl(?string $slug = null, ?string $explicitUrl = null): ?string
    {
        if (!empty($explicitUrl)) {
            return self::resolveAbsoluteImageUrl($explicitUrl);
        }

        if (empty($slug)) {
            return null;
        }

        $canonicalBase = config('seo.canonical_url') ?: (config('app.url') ?: url('/'));
        $canonicalBase = rtrim((string) $canonicalBase, '/');

        return $canonicalBase . '/products/' . ltrim($slug, '/');
    }

    /**
     * Build the prefilled WhatsApp enquiry message for a product.
     *
     * Format:
     *   Radhe Radhe 🙏
     *   I would like to enquire about this product.
     *
     *   Product: {title}
     *   Product ID: {id}
     *   Product Image: {public_image_url}   (omitted if no image)
     *   Product Page: {public_product_url} (omitted if no page)
     *
     *   Please share the price, availability and delivery details.
     *
     *   Thank you.
     *   Harivanshi Seva Kunj
     *
     * @param array<string, mixed>|Product $product
     */
    public static function buildProductEnquiryMessage(array|Product $product): string
    {
        if ($product instanceof Product) {
            $name = $product->title;
            $id = $product->id;
            $imageUrl = self::resolveAbsoluteImageUrl($product->image_url);
            $productUrl = self::resolveAbsoluteProductUrl($product->slug);
        } else {
            $name = $product['name'] ?? ($product['title'] ?? 'Product');
            $id = $product['id'] ?? null;
            $rawImage = $product['image'] ?? ($product['image_url'] ?? null);
            $imageUrl = self::resolveAbsoluteImageUrl($rawImage);
            $productUrl = self::resolveAbsoluteProductUrl($product['slug'] ?? null, $product['product_url'] ?? null);
        }

        $lines = [
            "Radhe Radhe 🙏",
            "I would like to enquire about this product.",
            "",
            "Product: " . trim((string) $name),
        ];

        if (!empty($id)) {
            $lines[] = "Product ID: " . $id;
        }

        if (!empty($imageUrl)) {
            $lines[] = "Product Image: " . $imageUrl;
        }

        if (!empty($productUrl)) {
            $lines[] = "Product Page: " . $productUrl;
        }

        $lines[] = "";
        $lines[] = "Please share the price, availability and delivery details.";
        $lines[] = "";
        $lines[] = "Thank you.";
        $lines[] = "Harivanshi Seva Kunj";

        return implode("\n", $lines);
    }

    /**
     * Generate the wa.me click-to-chat URL with safe RFC 3986 encoding.
     *
     * @param array<string, mixed>|Product $product
     * @param string|null $customNumber
     */
    public static function generateProductEnquiryUrl(array|Product $product, ?string $customNumber = null): ?string
    {
        $number = $customNumber ? self::formatPhoneNumber($customNumber) : self::getBusinessNumber();

        if (empty($number)) {
            return null;
        }

        $message = self::buildProductEnquiryMessage($product);

        return 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
    }
}
