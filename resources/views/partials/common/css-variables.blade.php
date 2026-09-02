{{-- Custom CSS variables. The AdminLTE v4 theming is driven by the Bootstrap
     5.3 and AdminLTE custom properties, so overriding them is enough for most
     brandings and needs no stylesheet of your own. --}}

@php
    // Only well formed custom property names are accepted, and a value that
    // could break out of the declaration is dropped, so a configuration value
    // cannot inject arbitrary CSS.

    $cssVarsSanitize = function ($cfg) {
        $vars = [];

        if (! is_array($cfg)) {
            return $vars;
        }

        foreach ($cfg as $name => $value) {
            $name = is_string($name) ? trim($name) : '';
            $value = is_scalar($value) ? trim((string) $value) : '';

            if (! preg_match('/^--[a-zA-Z0-9_-]+$/', $name)) {
                continue;
            }

            if ($value === '' || preg_match('/[;{}<>\\\\]|\/\*|@import|expression\s*\(/i', $value)) {
                continue;
            }

            $vars[$name] = $value;
        }

        return $vars;
    };

    $cssVars = $cssVarsSanitize(config('adminlte.css_variables', []));
    $cssVarsSidebar = $cssVarsSanitize(config('adminlte.css_variables_sidebar', []));

    $cssVarsScope = config('adminlte.css_variables_scope', ':root');
    $cssVarsScope = in_array($cssVarsScope, [':root', 'body'], true)
        ? $cssVarsScope
        : ':root';

    // AdminLTE redeclares the sidebar properties on the sidebar element under
    // a color mode selector, which beats any ':root' declaration. So, the
    // sidebar block matches that specificity and comes later on the document.

    $cssVarsSidebarScope = '[data-bs-theme] .app-sidebar, [data-bs-theme].app-sidebar, .app-sidebar';
@endphp

@if(! empty($cssVars) || ! empty($cssVarsSidebar))
    <style>
        @if(! empty($cssVars))
            {{ $cssVarsScope }} {
                @foreach($cssVars as $cssVarName => $cssVarValue)
                    {{ $cssVarName }}: {{ $cssVarValue }};
                @endforeach
            }
        @endif

        @if(! empty($cssVarsSidebar))
            {{ $cssVarsSidebarScope }} {
                @foreach($cssVarsSidebar as $cssVarName => $cssVarValue)
                    {{ $cssVarName }}: {{ $cssVarValue }};
                @endforeach
            }
        @endif
    </style>
@endif
