@props([
    'callback' => '',
    'errorCallback' => '',
    'theme' => 'auto',
    'language' => 'en',
    'size' => 'flexible',
])

<div
    {{ $attributes->merge([
        'class' => 'cf-turnstile',
        'data-sitekey' => config('services.turnstile.site_key'),
        'data-callback' => $callback,
        'data-error-callback' => $errorCallback,
        'data-theme' => $theme,
        'data-language' => $language,
        'data-size' => $size,
    ]) }}>
</div>
