<div class="alert alert-{{$type}} {{($dismissable) ? 'alert-dismissible' : ''}}">
    @if($dismissable)
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    @endif

    <h5><i class="icon fas fa-{{$icon}}"></i> {{$title}}</h5>
    {{$slot}}
</div>