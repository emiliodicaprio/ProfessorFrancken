<?php

declare(strict_types=1);

namespace Francken\Shared\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<string>
     */
    protected $except = [
        // We want people to be able to register from franckensymposium.nl
        '/symposia/*/participants'
    ];
}
