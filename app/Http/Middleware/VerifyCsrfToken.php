<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'register',
        'api/*',
        '*/register',
        '*/login',
        '*/logout',
        '*/me',
        '*/add-to-cart',
        '*/remove-from-cart',
        '*/add-to-wishlist',
        '*/remove-from-wishlist',
        '*/subscribe',
        '*/contact-submit',
        '*/home',
        '*/pages',
        '*/{slug}',
        '*/pro/{url}',
        '*/clear-optimization',
        '*/search',
        '*/wishlist',  
        '*/locale/sync',
        '*/auth/send-welcome-email',
    ];
}
