<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Support;

use Illuminate\Support\Facades\Config;

final readonly class LocaleConfig
{
    public function fallback(): string
    {
        return Config::string('app.fallback_locale', Config::string('app.locale', 'en'));
    }

    /**
     * @return array<int, string>
     */
    public function available(): array
    {
        return array_values(array_filter(
            Config::array('app.available_locales', []),
            is_string(...),
        ));
    }

    /**
     * Return the locale if the application supports it, otherwise the fallback.
     */
    public function supported(string $locale): string
    {
        if (in_array($locale, $this->available(), true)) {
            return $locale;
        }

        return $this->fallback();
    }
}
