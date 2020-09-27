<div class="info-box {{$background }}">
    <span class="info-box-icon {{$foreground}}">
        <i class="{{$icon}}"></i>
    </span>
    <div class="info-box-content">
        <span class="info-box-text" id="{{!is_null($id) ? $id.'-title' : null}}">
            {{$title}}
        </span>
        <span class="info-box-number" id="{{!is_null($id) ? $id.'-text' : null}}">
            {{$text}}
        </span>
        @if($progress)
        <div class="progress">
            <div class="progress-bar" id="{{!is_null($id) ? $id.'-progress' : null}}" style="width: {{$progress}}%"></div>
        </div>
        @endif 

        @if($comment)
        <span class="progress-description" id="{{!is_null($id) ? $id.'-comment' : null}}">
            {{$comment}}
        </span>
        @endif
    </div>
</div>