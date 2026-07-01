<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Misaf\VendraLocalization\Contracts\LocaleResolver;
use Misaf\VendraLocalization\Support\LocaleConfig;
use Symfony\Component\HttpFoundation\Response;

final readonly class SetLocale
{
    public function __construct(
        private LocaleResolver $localeResolver,
        private LocaleConfig $localeConfig,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->localeConfig->supported(
            $this->localeResolver->resolve($request),
        );

        App::setLocale($locale);

        $response = $next($request);
        $response->headers->set('Content-Language', $locale);
        $response->headers->set('Vary', 'Accept-Language', false);

        return $response;
    }
}
