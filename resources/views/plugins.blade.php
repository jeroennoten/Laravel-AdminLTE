@foreach(config('adminlte.plugins') as $pluginName => $plugin)
    @if($plugin['active'] || View::getSection('plugins.' . ($plugin['name'] ?? $pluginName)))
        @foreach($plugin['files'] as $file)

            {{-- Check requested file type --}}
            @if($file['type'] == $type && $type == 'css')
                <link rel="stylesheet" href="{{ $file['asset'] ? asset($file['location']) : $file['location'] }}">
            @elseif($file['type'] == $type && $type == 'js')
                <script src="{{ $file['asset'] ? asset($file['location']) : $file['location'] }}"></script>
            @endif

        @endforeach
    @endif
@endforeach
