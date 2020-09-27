<div class="small-box bg-{{$bg}}">
    @if($loading)
    <div class="overlay dark">
        <i class="fas fa-3x fa-sync-alt"></i>
    </div>
    @endif
    <div class="inner">
        <h3 id="{{!is_null($id) ? $id.'-text' : null}}">{{$text}}</h3>
        <p id="{{!is_null($id) ? $id.'-title' : null}}">{{$title}}</p>
    </div>
    <div class="icon">
        <i class="{{$icon}}"></i>
    </div>
    <a id="{{!is_null($id) ? $id.'-link' : null}}" href="{{$url}}" class="small-box-footer"> 
        {{$urlTextLine}} <i class="fas fa-arrow-circle-right"></i>
    </a>
</div>