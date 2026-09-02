<div {{ $attributes->merge(['class' => $makeAlertClass(), 'role' => 'alert']) }}>

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

    {{-- Dismiss button (Bootstrap v5 markup). It is absolutely positioned by
         '.alert-dismissible', so it comes last and a screen reader reaches the
         message before the button. --}}
    @if(! empty($dismissable))
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('adminlte::adminlte.close') }}"></button>
    @endif

</div>
