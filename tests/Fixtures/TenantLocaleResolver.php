<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Tests\Fixtures;

use Illuminate\Http\Request;
use Misaf\VendraLocalization\Contracts\LocaleResolver;

final class TenantLocaleResolver implements LocaleResolver
{
    public function resolve(Request $request): string
    {
        return 'fa';
    }
}
