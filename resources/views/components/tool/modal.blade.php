<div {{ $attributes->merge(['class' => $makeModalClass(), 'id' => $id]) }}
     @isset($staticBackdrop) data-backdrop="static" data-keyboard="false" @endisset>

    <div class="{{ $makeModalDialogClass() }}">
    <div class="modal-content">

        {{--Modal header --}}
        <div class="{{ $makeModalHeaderClass() }}">
            <h4 class="modal-title">
                @isset($icon)<i class="{{ $icon }} mr-2"></i>@endisset
                @isset($title){{ $title }}@endisset
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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
                <x-adminlte-button class="{{ $makeCloseButtonClass }}"
                    data-dismiss="modal" label="Close"/>
            @endisset
        </div>

    </div>
    </div>

</div>
