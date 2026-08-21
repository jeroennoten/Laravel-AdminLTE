<div {{ $attributes->merge(['class' => $makeCardClass()]) }}>

    {{-- Card header --}}
    @if(! $isCardHeaderEmpty(isset($toolsSlot)))
        <div class="{{ $makeCardHeaderClass() }}">

            {{-- Title --}}
            <h3 class="{{ $makeCardTitleClass() }}">
                @isset($icon)<i class="{{ $icon }} me-1" aria-hidden="true"></i>@endisset
                @isset($title){{ $title }}@endisset
            </h3>

            {{-- Tools --}}
            <div class="card-tools">

                {{-- Extra tools slot --}}
                @isset($toolsSlot)
                    {{ $toolsSlot }}
                @endisset

                {{-- Maximize tool --}}
                @isset($maximizable)
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-maximize"
                        aria-label="Maximize card">
                        <i data-lte-icon="maximize" class="bi bi-fullscreen"></i>
                        <i data-lte-icon="minimize" class="bi bi-fullscreen-exit"></i>
                    </button>
                @endisset

                {{-- Collapse tool (the icon visibility is handled by AdminLTE) --}}
                @isset($collapsible)
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"
                        aria-label="Collapse card">
                        <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                        <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                    </button>
                @endisset

                {{-- Remove tool --}}
                @isset($removable)
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-remove"
                        aria-label="Remove card">
                        <i class="bi bi-x-lg"></i>
                    </button>
                @endisset

            </div>

        </div>
    @endif

    {{-- Card body --}}
    @if(! $slot->isEmpty())
        <div class="{{ $makeCardBodyClass() }}">
            {{ $slot }}
        </div>
    @endif

    {{-- Card footer --}}
    @isset($footerSlot)
        <div class="{{ $makeCardFooterClass() }}">
            {{ $footerSlot }}
        </div>
    @endisset

    {{-- Card overlay (shown when the card is disabled) --}}
    @if($disabled)
        <div class="card-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-body bg-opacity-75 rounded"
            style="z-index:20;">
            <i class="bi bi-slash-circle fs-2 text-body-secondary" aria-hidden="true"></i>
            <span class="visually-hidden">Disabled</span>
        </div>
    @endif

</div>
