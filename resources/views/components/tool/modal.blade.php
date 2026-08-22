<div {{ $attributes->merge(['class' => $makeModalClass(), 'id' => $id]) }}
     tabindex="-1" aria-labelledby="{{ $id }}-title" aria-hidden="true"
     @isset($staticBackdrop) data-bs-backdrop="static" data-bs-keyboard="false" @endisset>

    <div class="{{ $makeModalDialogClass() }}">
    <div class="modal-content">

        {{--Modal header --}}
        <div class="{{ $makeModalHeaderClass() }}" {!! $makeModalHeaderData() !!}>
            <h4 class="modal-title" id="{{ $id }}-title">
                @isset($icon)<i class="{{ $icon }} me-2"></i>@endisset
                @isset($title){{ $title }}@endisset
            </h4>
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
                <x-adminlte-button :theme="$theme ?? 'secondary'"
                    data-bs-dismiss="modal" :label="__('adminlte::adminlte.close')"/>
            @endisset
        </div>

    </div>
    </div>

</div>
