<div {{ $attributes->merge(['class' => $makeAlertClass()]) }}>

    {{-- Dismiss button --}}
    @isset($dismissable)
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            &times;
        </button>
    @endisset

    {{-- Alert title --}}
    <h5><i class="icon {{ $icon }}"></i> {{ $title }}</h5>

    {{-- Alert content --}}
    {{ $slot }}

</div>
