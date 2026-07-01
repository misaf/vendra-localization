<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Misaf\VendraLocalization\Contracts\LocaleResolver;
use Misaf\VendraLocalization\Resolvers\AcceptLanguageLocaleResolver;
use Misaf\VendraLocalization\Resolvers\FallbackLocaleResolver;
use Misaf\VendraLocalization\Resolvers\QueryLocaleResolver;
use Misaf\VendraLocalization\Resolvers\RouteLocaleResolver;
use Misaf\VendraLocalization\Resolvers\UserLocaleResolver;
use Misaf\VendraLocalization\Tests\Fixtures\TenantLocaleResolver;
use Misaf\VendraLocalization\Tests\TestCase;

uses(TestCase::class);

it('binds the configured resolver implementation', function (): void {
    config()->set('vendra-localization.resolver', TenantLocaleResolver::class);

    app()->forgetInstance(LocaleResolver::class);

    $resolver = app(LocaleResolver::class);

    expect($resolver)
        ->toBeInstanceOf(TenantLocaleResolver::class)
        ->and($resolver->resolve(Request::create('/')))
        ->toBe('fa');
});

it('resolves the preferred accept language from available app locales', function (): void {
    $request = Request::create('/', server: [
        'HTTP_ACCEPT_LANGUAGE' => 'fr-CA,fr;q=0.9,de;q=0.8,en;q=0.7',
    ]);

    expect(app(AcceptLanguageLocaleResolver::class)->resolve($request))->toBe('fr');
});

it('resolves locale from the query string', function (): void {
    $request = Request::create('/?locale=de');

    expect(app(QueryLocaleResolver::class)->resolve($request))->toBe('de');
});

it('resolves locale from the route parameter', function (): void {
    $request = Request::create('/api/fa/products');
    $route = new Route('GET', '/api/{locale}/products', []);
    $route->bind($request);
    $request->setRouteResolver(fn(): Route => $route);

    expect(app(RouteLocaleResolver::class)->resolve($request))->toBe('fa');
});

it('resolves locale from the authenticated user', function (): void {
    $request = Request::create('/');
    $request->setUserResolver(fn(): object => new class () {
        public string $locale = 'de';
    });

    expect(app(UserLocaleResolver::class)->resolve($request))->toBe('de');
});

it('resolves the configured fallback locale', function (): void {
    config()->set('app.fallback_locale', 'fa');

    expect(app(FallbackLocaleResolver::class)->resolve(Request::create('/')))->toBe('fa');
});
