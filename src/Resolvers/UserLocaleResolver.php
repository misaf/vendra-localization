<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Resolvers;

use Illuminate\Http\Request;
use Misaf\VendraLocalization\Contracts\LocaleResolver;
use Misaf\VendraLocalization\Support\LocaleConfig;

final readonly class UserLocaleResolver implements LocaleResolver
{
    public function __construct(
        private LocaleConfig $localeConfig,
    ) {}

    public function resolve(Request $request): string
    {
        $locale = $request->user()?->locale;

        if (is_string($locale) && '' !== $locale) {
            return $locale;
        }

        return $this->localeConfig->fallback();
    }
}
