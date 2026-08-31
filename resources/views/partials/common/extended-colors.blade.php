@inject('utilsHelper', 'JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper')

@php($extendedColors = $utilsHelper->getExtendedColors())

@if(! empty($extendedColors))
    {{--
        The optional AdminLTE palette stylesheet provides the '.bg-*',
        '.text-bg-*', '.text-*', '.border-*', '.link-*', '.bg-gradient-*',
        '.card-*', '.callout-*' and '.direct-chat-*' families, but neither
        '.alert-*' nor '.btn-*'. Both are generated here from the custom
        properties that the same stylesheet defines for every color, so a
        component themed with an extended color renders as expected.
    --}}
    <style>
        @foreach($extendedColors as $color)
        .alert-{{ $color }} {
            --bs-alert-color: var(--bs-{{ $color }}-text-emphasis);
            --bs-alert-bg: var(--bs-{{ $color }}-bg-subtle);
            --bs-alert-border-color: var(--bs-{{ $color }}-border-subtle);
            --bs-alert-link-color: var(--bs-{{ $color }}-text-emphasis);
        }

        .btn-{{ $color }} {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--bs-{{ $color }});
            --bs-btn-border-color: var(--bs-{{ $color }});
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: color-mix(in srgb, var(--bs-{{ $color }}) 85%, #000);
            --bs-btn-hover-border-color: color-mix(in srgb, var(--bs-{{ $color }}) 80%, #000);
            --bs-btn-focus-shadow-rgb: var(--bs-{{ $color }}-rgb);
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: color-mix(in srgb, var(--bs-{{ $color }}) 80%, #000);
            --bs-btn-active-border-color: color-mix(in srgb, var(--bs-{{ $color }}) 75%, #000);
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: var(--bs-{{ $color }});
            --bs-btn-disabled-border-color: var(--bs-{{ $color }});
        }

        .btn-outline-{{ $color }} {
            --bs-btn-color: var(--bs-{{ $color }});
            --bs-btn-border-color: var(--bs-{{ $color }});
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--bs-{{ $color }});
            --bs-btn-hover-border-color: var(--bs-{{ $color }});
            --bs-btn-focus-shadow-rgb: var(--bs-{{ $color }}-rgb);
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: var(--bs-{{ $color }});
            --bs-btn-active-border-color: var(--bs-{{ $color }});
            --bs-btn-disabled-color: var(--bs-{{ $color }});
            --bs-btn-disabled-border-color: var(--bs-{{ $color }});
            --bs-gradient: none;
        }
        @endforeach
    </style>
@endif
