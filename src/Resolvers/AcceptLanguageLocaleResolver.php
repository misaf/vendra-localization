<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Resolvers;

use Illuminate\Http\Request;
use Misaf\VendraLocalization\Contracts\LocaleResolver;
use Misaf\VendraLocalization\Support\LocaleConfig;

final readonly class AcceptLanguageLocaleResolver implements LocaleResolver
{
    public function __construct(
        private LocaleConfig $localeConfig,
    ) {}

    public function resolve(Request $request): string
    {
        return $request->getPreferredLanguage($this->localeConfig->available())
            ?? $this->localeConfig->fallback();
    }
}
