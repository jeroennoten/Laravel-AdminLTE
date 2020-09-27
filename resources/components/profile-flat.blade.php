<div class="card card-widget widget-user-2">
    <div class="widget-user-header {{$background}}">
        <div class="widget-user-image">
            <img class="img-circle elevation-2" src="{{$img}}" alt="User Avatar">
        </div>
        <h3 class="widget-user-username">{{$name}}</h3>
        <h5 class="widget-user-desc">{{$desc}}</h5>
    </div>
    <div class="card-footer p-0">
        <ul class="nav flex-column">
            {{$slot}}
        </ul>
    </div>
</div>