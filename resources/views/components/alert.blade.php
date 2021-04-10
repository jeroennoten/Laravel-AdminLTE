<div {{ $attributes->merge(['class' => $makeAlertClass()]) }}>

    {{-- Dismiss button --}}
    @isset($dismissable)
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            &times;
        </button>
    @endisset

    {{-- Alert title --}}
    @if(!is_null($title))
        <h5>@if($icon)<i class="icon {{ $icon }}"></i>@endif {{ $title }}</h5>
    @endif

    {{-- Alert content --}}
    {{ $slot }}

</div>
