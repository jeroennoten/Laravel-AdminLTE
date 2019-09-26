@foreach(config('adminlte.plugins') as $plugin)
    @if($plugin['active'] || View::getSection('plugins.' . $plugin['name']))
        @foreach($plugin['files'] as $file)
            @if($file['type'] == $type)
                @if($type == 'css')
                    @if($file['asset'])
                        <link rel="stylesheet" href="{{ asset($file['location']) }}">
                    @else
                        <link rel="stylesheet" href="{{$file['location']}}">
                    @endif
                @elseif($type == 'js')
                    @if($file['asset'])
                        <script src="{{ asset($file['location']) }}"></script>
                    @else
                        <script src="{{$file['location']}}"></script>
                    @endif
                @endif
            @endif
        @endforeach
    @endif
@endforeach
