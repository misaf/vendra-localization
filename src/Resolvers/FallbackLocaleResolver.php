<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Resolvers;

use Illuminate\Http\Request;
use Misaf\VendraLocalization\Contracts\LocaleResolver;
use Misaf\VendraLocalization\Support\LocaleConfig;

final readonly class FallbackLocaleResolver implements LocaleResolver
{
    public function __construct(
        private LocaleConfig $localeConfig,
    ) {}

    public function resolve(Request $request): string
    {
        return $this->localeConfig->fallback();
    }
}
