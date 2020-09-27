<div class="callout callout-{{$type}}">
    @if(!is_null($title))
    <h5>{{$title}}</h5>
    @endif
    <p>{{$slot}}</p>
</div>