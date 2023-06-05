@foreach(config('adminlte.plugins') as $pluginName => $plugin)

    {{-- Check whether the plugin is active --}}

    @php
        $plugSection = View::getSection('plugins.' . ($plugin['name'] ?? $pluginName));
        $isPlugActive = $plugin['active']
            ? ! isset($plugSection) || $plugSection
            : ! empty($plugSection);
    @endphp

    {{-- When the plugin is active, include its files --}}

    @if($isPlugActive)
        @foreach($plugin['files'] as $file)

            {{-- Setup the file location --}}

            @php
                if (! empty($file['asset'])) {
                    $file['location'] = asset($file['location']);
                }
            @endphp

            {{-- Check the requested file type --}}

            @if($file['type'] == $type && $type == 'css')
                <link rel="stylesheet" href="{{ $file['location'] }}">
            @elseif($file['type'] == $type && $type == 'js')
                <script src="{{ $file['location'] }}" @if(! empty($file['defer'])) defer @endif></script>
            @endif

        @endforeach
    @endif

@endforeach
