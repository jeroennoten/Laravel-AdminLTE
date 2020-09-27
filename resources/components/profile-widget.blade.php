<div class="card card-widget widget-user">
    @if(!is_null($cover))
    <div class="widget-user-header text-white" style="background: url('{{$cover}}') center center;">
    @else
    <div class="widget-user-header {{$background}}">
    @endif
        <h3 class="widget-user-username">{{$name}}</h3>
        <h5 class="widget-user-desc">{{$desc}}</h5>
    </div>
    <div class="widget-user-image">
        <img class="img-circle elevation-2" src="{{$img}}" alt="User Avatar: {{$name}}">
    </div>
    <div class="card-footer">
        <div class="row">
            {{$slot}}
        </div>
    </div>
</div>