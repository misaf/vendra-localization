<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Misaf\VendraLocalization\Contracts\LocaleResolver;
use Misaf\VendraLocalization\Http\Middleware\SetLocale;
use Misaf\VendraLocalization\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

uses(TestCase::class);

it('sets the resolved supported locale and response headers', function (): void {
    app()->instance(LocaleResolver::class, new class () implements LocaleResolver {
        public function resolve(Request $request): string
        {
            return 'fa';
        }
    });

    $response = app(SetLocale::class)->handle(
        Request::create('/'),
        fn(Request $request): Response => new Response('ok'),
    );

    expect(app()->getLocale())
        ->toBe('fa')
        ->and($response->headers->get('Content-Language'))
        ->toBe('fa')
        ->and($response->headers->get('Vary'))
        ->toBe('Accept-Language');
});

it('falls back when the resolved locale is unsupported', function (): void {
    config()->set('app.fallback_locale', 'en');
    config()->set('app.available_locales', ['en', 'fa']);

    app()->instance(LocaleResolver::class, new class () implements LocaleResolver {
        public function resolve(Request $request): string
        {
            return 'es';
        }
    });

    $response = app(SetLocale::class)->handle(
        Request::create('/'),
        fn(Request $request): Response => new Response('ok'),
    );

    expect(app()->getLocale())
        ->toBe('en')
        ->and($response->headers->get('Content-Language'))
        ->toBe('en');
});
