@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('assetHelper', 'JeroenNoten\LaravelAdminLte\Helpers\AssetHelper')

@php
    $isRtlEnabled = $layoutHelper->isRtlEnabled();

    // A configuration that is not a set of plugins is ignored, so a wrong
    // value does not break every page of the panel.

    $plugins = config('adminlte.plugins', []);
    $plugins = is_array($plugins) ? $plugins : [];
@endphp

@foreach($plugins as $pluginName => $plugin)

    {{-- Check whether the plugin is active --}}

    @php
        $plugSection = View::getSection('plugins.' . ($plugin['name'] ?? $pluginName));
        $isPlugActive = ! empty($plugin['active'])
            ? ! isset($plugSection) || $plugSection
            : ! empty($plugSection);
    @endphp

    {{-- When the plugin is active, include its files --}}

    @if($isPlugActive)
        @foreach($plugin['files'] ?? [] as $file)

            {{-- Setup the file location. When available, the 'rtl' location
                 replaces the default one on the RTL mode. --}}

            @php
                $location = $file['location'] ?? null;

                if ($isRtlEnabled && ! empty($file['rtl'])) {
                    $location = $file['rtl'];
                }

                if (! empty($file['asset'])) {
                    $location = asset($location);
                }

                // A plugin may point to an asset of the AdminLTE distribution,
                // so the version placeholder is resolved here too.

                $location = $assetHelper->applyVersion($location);
            @endphp

            {{-- Check the requested file type --}}

            @if(! empty($location) && ($file['type'] ?? null) === $type)
                @if($type === 'css')
                    <link rel="stylesheet" href="{{ $location }}">
                @elseif($type === 'js')
                    <script src="{{ $location }}" @if(! empty($file['defer'])) defer @endif></script>
                @endif
            @endif

        @endforeach
    @endif

@endforeach
