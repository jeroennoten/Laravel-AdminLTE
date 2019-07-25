@foreach(config('adminlte.plugins') as $plugin)
    @if($plugin['active'])
        @foreach($plugin['files'] as $file)
            @if($file['type'] == $type)
                @if($file['asset'])
                    <link rel="stylesheet" href="{{ asset($file['location']) }}">
                @else
                    <link rel="stylesheet" href="{{$file['location']}}">
                @endif
            @endif
        @endforeach
    @endif
@endforeach
