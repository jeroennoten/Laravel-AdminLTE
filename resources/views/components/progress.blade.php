<div class="progress {{$barsize}} {{$vertical ? 'vertical' : ''}}">
    <div class="progress-bar bg-{{$bg}} {{$stripped ? 'progress-bar-striped' : ''}}" 
        role="progressbar" aria-valuenow="{{$value}}" aria-valuemin="0"
        aria-valuemax="100" 
        @if($vertical)
        style="height: {{$value}}%"
        @else 
        style="width: {{$value}}%"
        @endif
        >
        <span class="sr-only">{{$value}}% Progress</span>
    </div>
</div>