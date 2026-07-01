<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Locale Resolver
    |--------------------------------------------------------------------------
    |
    | Any class implementing
    | Misaf\VendraLocalization\Contracts\LocaleResolver.
    |
    */

    'resolver' => Misaf\VendraLocalization\Resolvers\AcceptLanguageLocaleResolver::class,

];
