@php
    // Setup the set of social authentication links. Since these links come
    // from the configuration file, every value is validated here to ensure a
    // configuration value can not inject markup or an arbitrary class into the
    // authentication views.

    $asText = function ($value) {
        return is_scalar($value) ? trim((string) $value) : '';
    };

    // The theme is restricted to the set of Bootstrap 5.3 button themes and
    // the icon to a short set of plain class tokens.

    $themePattern = '/^(outline-)?(primary|secondary|success|danger|warning|info|light|dark|link)$/';
    $iconPattern = '/^[A-Za-z0-9_-]+( [A-Za-z0-9_-]+){0,4}$/';

    $fallbackText = $fallbackText ?? __('adminlte::adminlte.sign_in');
    $socialLinks = [];

    foreach ((array) config('adminlte.auth_social_links', []) as $socialLink) {
        $socialLink = (array) $socialLink;
        $url = $asText($socialLink['url'] ?? null);

        if ($url === '') {
            continue;
        }

        // A social login target is always an http(s) url or an application
        // relative path, so any other scheme is dropped. This keeps a
        // 'javascript:' url out of the markup even when it reaches the
        // configuration file by accident.

        $scheme = parse_url($url, PHP_URL_SCHEME);

        if (isset($scheme) && ! in_array(strtolower($scheme), ['http', 'https'], true)) {
            continue;
        }

        if (! isset($scheme) && preg_match('/^\s*[a-z][a-z0-9+.-]*:/i', $url)) {
            continue;
        }

        $text = $asText($socialLink['text'] ?? null);
        $icon = $asText($socialLink['icon'] ?? null);
        $theme = $asText($socialLink['theme'] ?? null);

        $socialLinks[] = [
            'url' => $url,
            'text' => $text !== '' ? $text : $fallbackText,
            'icon' => preg_match($iconPattern, $icon) ? $icon : null,
            'theme' => preg_match($themePattern, $theme) ? $theme : 'primary',
        ];
    }

    // Setup the separator label. The translation key may be missing on the
    // translation files published by a previous version of the package, so a
    // default label is used in that case. The label can also be replaced or
    // disabled (using an empty value) from the configuration file.

    $separator = config('adminlte.auth_social_links_separator');

    if ($separator === null) {
        $separator = Lang::has('adminlte::adminlte.social_auth_separator')
            ? __('adminlte::adminlte.social_auth_separator')
            : '- OR -';
    }

    $separator = $asText($separator);
@endphp
@if (! empty($socialLinks))
    {{-- Social Authentication Links --}}
    <div class="social-auth-links text-center mb-3 d-grid gap-2">

        @if ($separator !== '')
            <p>{{ $separator }}</p>
        @endif

        @foreach ($socialLinks as $socialLink)
            <a href="{{ $socialLink['url'] }}" class="btn btn-{{ $socialLink['theme'] }}">
                @if ($socialLink['icon'])
                    <i class="{{ $socialLink['icon'] }} me-2"></i>
                @endif
                {{ $socialLink['text'] }}
            </a>
        @endforeach

    </div>
@endif
