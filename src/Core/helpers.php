<?php

declare(strict_types=1);

use App\Core\Request;
use App\Core\Auth;

if (!function_exists('url')) {
    /**
     * Generate relative URL with application base path prefixed.
     */
    function url(string $path = ''): string
    {
        $base = Request::basePath();
        $trimmedPath = '/' . ltrim($path, '/');
        
        if ($trimmedPath === '/' && $base !== '') {
            return $base;
        }

        return $base . $trimmedPath;
    }
}

if (!function_exists('asset')) {
    /**
     * Generate URL for static assets.
     */
    function asset(string $path): string
    {
        return url($path);
    }
}

if (!function_exists('full_url')) {
    /**
     * Generate absolute URL including scheme and host.
     */
    function full_url(string $path = ''): string
    {
        $base = Request::baseUrl();
        $trimmedPath = '/' . ltrim($path, '/');
        
        if ($trimmedPath === '/') {
            return $base;
        }

        return rtrim($base, '/') . $trimmedPath;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return Auth::csrfToken();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
