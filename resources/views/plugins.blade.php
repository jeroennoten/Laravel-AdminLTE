@foreach(config('adminlte.plugins') as $pluginName => $plugin)
    @if($plugin['active'] || View::getSection('plugins.' . ($plugin['name'] ?? $pluginName)))
        @foreach($plugin['files'] as $file)

            {{-- Setup the file location  --}}
            @php
                if (! empty($file['asset'])) {
                    $file['location'] = asset($file['location']);
                }
            @endphp

            {{-- Check requested file type --}}
            @if($file['type'] == $type && $type == 'css')
                <link rel="stylesheet" href="{{ $file['location'] }}">
            @elseif($file['type'] == $type && $type == 'js')
                <script src="{{ $file['location'] }}" @if(! empty($file['defer'])) defer @endif></script>
            @endif

        @endforeach
    @endif
@endforeach
