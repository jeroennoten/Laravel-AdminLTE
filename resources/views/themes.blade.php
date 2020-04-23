@if(config('adminlte.theme.active'))
    @php 
        $themes =  config('adminlte.themes');
        $theme = $themes[config('adminlte.theme.name')];
    @endphp
    <link rel="stylesheet" href="{{asset('vendor/adminlte-themes/'.$theme)}}" />
@endif