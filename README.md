# Vendra Localization

Request locale resolution for Laravel applications.

## Features

- `SetLocale` middleware that resolves the request locale, calls `App::setLocale()`, and sets the `Content-Language` and `Vary: Accept-Language` response headers
- Pluggable, config-driven `LocaleResolver` strategy
- Built-in resolvers for the `Accept-Language` header, query string, route parameter, authenticated user, and a plain fallback
- Falls back to `app.fallback_locale` (then `app.locale`) whenever the resolved locale is unsupported
- Supported locales are read from the `app.available_locales` config array

## Requirements

- PHP 8.3+
- Laravel 12 or 13
- Pest 4

## Installation

```bash
composer require misaf/vendra-localization
```

Publish the config file:

```bash
php artisan vendor:publish --tag=vendra-localization-config
```

The service provider is auto-registered.

## Configuration

Define the locales your application serves in `config/app.php`:

```php
'available_locales' => ['en', 'fa', 'de'],
```

Choose the resolver strategy in `config/vendra-localization.php`:

```php
return [
    'resolver' => Misaf\VendraLocalization\Resolvers\AcceptLanguageLocaleResolver::class,
];
```

## Usage

Register the middleware on the group or routes that should be localized, e.g. in `bootstrap/app.php`:

```php
use Misaf\VendraLocalization\Http\Middleware\SetLocale;

->withMiddleware(function (Middleware $middleware): void {
    $middleware->web(append: [
        SetLocale::class,
    ]);
})
```

On each request the middleware resolves a locale through the configured resolver, validates it against `app.available_locales`, applies it with `App::setLocale()`, and adds the response headers:

```
Content-Language: fa
Vary: Accept-Language
```

## Resolvers

| Resolver | Reads the locale from |
| --- | --- |
| `AcceptLanguageLocaleResolver` | The `Accept-Language` request header (default) |
| `QueryLocaleResolver` | The `?locale=` query string parameter |
| `RouteLocaleResolver` | The `{locale}` route parameter |
| `UserLocaleResolver` | The authenticated user's `locale` attribute |
| `FallbackLocaleResolver` | Always `app.fallback_locale` (then `app.locale`) |

Every resolver falls back to `app.fallback_locale` when its source has no value.

## Custom resolver

Implement the `LocaleResolver` contract and point the config at it:

```php
use Illuminate\Http\Request;
use Misaf\VendraLocalization\Contracts\LocaleResolver;

final readonly class TenantLocaleResolver implements LocaleResolver
{
    public function resolve(Request $request): string
    {
        return $request->user()?->tenant?->locale ?? 'en';
    }
}
```

```php
// config/vendra-localization.php
'resolver' => App\Localization\TenantLocaleResolver::class,
```

The resolved locale is still validated against `app.available_locales` by the middleware, so an unsupported value falls back automatically.

## Testing

```bash
composer test
```

## License

MIT.
