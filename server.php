<?php

/**
 * Laravel - Local dev server router with Cache-Control headers
 *
 * Emulates Apache mod_rewrite and sets efficient cache lifetimes for static assets.
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$publicFile = __DIR__.'/public'.$uri;

if ($uri !== '/' && file_exists($publicFile) && !is_dir($publicFile)) {
    $extension = strtolower(pathinfo($publicFile, PATHINFO_EXTENSION));

    $mimes = [
        'css'   => 'text/css; charset=UTF-8',
        'js'    => 'application/javascript; charset=UTF-8',
        'webp'  => 'image/webp',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'svg'   => 'image/svg+xml',
        'ico'   => 'image/x-icon',
        'woff2' => 'font/woff2',
        'woff'  => 'font/woff',
        'ttf'   => 'font/ttf',
        'json'  => 'application/json',
    ];

    if (isset($mimes[$extension])) {
        header('Content-Type: '.$mimes[$extension]);
        header('Content-Length: '.filesize($publicFile));
        header('Cache-Control: public, max-age=31536000, immutable');
        header('Access-Control-Allow-Origin: *');

        readfile($publicFile);
        return true;
    }

    return false;
}

require_once __DIR__.'/public/index.php';
