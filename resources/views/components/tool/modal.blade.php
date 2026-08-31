<div {{ $attributes->merge(['class' => $makeModalClass(), 'id' => $id]) }}
     tabindex="-1" aria-labelledby="{{ $id }}-title" aria-hidden="true"
     @isset($staticBackdrop) data-bs-backdrop="static" data-bs-keyboard="false" @endisset>

    <div class="{{ $makeModalDialogClass() }}">
    <div class="modal-content">

        {{--Modal header --}}
        <div class="{{ $makeModalHeaderClass() }}" {!! $makeModalHeaderData() !!}>
            <h1 class="modal-title fs-5" id="{{ $id }}-title">
                @isset($icon)<i class="{{ $icon }} me-2"></i>@endisset
                @isset($title){{ $title }}@endisset
            </h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="{{ __('adminlte::adminlte.close') }}"></button>
        </div>

        {{-- Modal body --}}
        @if(! $slot->isEmpty())
            <div class="modal-body">{{ $slot }}</div>
        @endif

        {{-- Modal footer --}}
        <div class="modal-footer">
            @isset($footerSlot)
                {{ $footerSlot }}
            @else
                <button type="button" class="{{ $makeCloseButtonClass }}" data-bs-dismiss="modal">
                    {{ __('adminlte::adminlte.close') }}
                </button>
            @endisset
        </div>

    </div>
    </div>

</div>
