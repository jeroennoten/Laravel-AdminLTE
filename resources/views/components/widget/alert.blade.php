<div {{ $attributes->merge(['class' => $makeAlertClass(), 'role' => 'alert']) }}>

    {{-- Dismiss button (Bootstrap v5 markup) --}}
    @if(! empty($dismissable))
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('adminlte::adminlte.close') }}"></button>
    @endif

    {{-- Alert header --}}
    @if(! empty($title) || ! empty($icon))
        <h5 class="alert-heading">
            @if(! empty($icon))
                <i class="{{ $icon }} me-2" aria-hidden="true"></i>
            @endif

            @if(! empty($title))
                {{ $title }}
            @endif
        </h5>
    @endif

    {{-- Alert content --}}
    {{ $slot }}

</div>
