<div {{ $attributes->merge(['class' => $makeModalClass(), 'id' => $id]) }}
     tabindex="-1" {!! $makeAriaLabelledBy() !!} aria-hidden="true"
     @isset($staticBackdrop) data-bs-backdrop="static" data-bs-keyboard="false" @endisset>

    <div class="{{ $makeModalDialogClass() }}">
    <div class="modal-content">

        {{--Modal header --}}
        <div class="{{ $makeModalHeaderClass() }}" {!! $makeModalHeaderData() !!}>
            {{-- An empty heading is an accessibility defect, so the heading is
                 only rendered when it holds a title to announce (the same
                 condition that emits the 'aria-labelledby' above). The close
                 button is pushed to the end by its own 'margin-left:auto'. --}}
            @if($hasTitle())
                <h1 class="modal-title fs-5" id="{{ $id }}-title">
                    @isset($icon)<i class="{{ $icon }} me-2" aria-hidden="true"></i>@endisset
                    {{ $title }}
                </h1>
            @elseif(isset($icon))
                <i class="{{ $icon }} me-2" aria-hidden="true"></i>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="{{ __('adminlte::adminlte.close') }}"></button>
        </div>

        {{-- Modal body --}}
        @if(! $slot->isEmpty())
            <div class="modal-body">{{ $slot }}</div>
        @endif

        {{-- Modal footer --}}
        @if(! isset($disableFooter))
            <div class="modal-footer">
                @isset($footerSlot)
                    {{ $footerSlot }}
                @else
                    <button type="button" class="{{ $makeCloseButtonClass }}" data-bs-dismiss="modal">
                        {{ __('adminlte::adminlte.close') }}
                    </button>
                @endisset
            </div>
        @endif

    </div>
    </div>

</div>
