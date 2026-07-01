<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Misaf\VendraLocalization\Contracts\LocaleResolver;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class LocalizationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-localization')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind(LocaleResolver::class, function (Application $app): LocaleResolver {
            /** @var class-string<LocaleResolver> $resolver */
            $resolver = Config::string('vendra-localization.resolver');

            $localeResolver = $app->make($resolver);

            if (! $localeResolver instanceof LocaleResolver) {
                throw new InvalidArgumentException(sprintf(
                    'Configured locale resolver [%s] must implement [%s].',
                    $resolver,
                    LocaleResolver::class,
                ));
            }

            return $localeResolver;
        });
    }
}
