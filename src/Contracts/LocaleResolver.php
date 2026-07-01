<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Contracts;

use Illuminate\Http\Request;

interface LocaleResolver
{
    /**
     * Resolve the preferred locale for the current request.
     */
    public function resolve(Request $request): string;
}
