<div {{ $attributes->merge(['class' => $makeCardClass(isset($tabsSlot))]) }}>

    {{-- Card header --}}
    @if(! $isCardHeaderEmpty(isset($toolsSlot), isset($titleSlot), isset($headerSlot), isset($tabsSlot)))
        <div class="{{ $makeCardHeaderClass(isset($tabsSlot)) }}"@if($disabled) inert @endif>

            {{-- Full header slot (replaces the title and the tools) --}}
            @isset($headerSlot)
                {{ $headerSlot }}
            @else

                {{-- Title. The reference (dist/docs/components/card.html) puts
                     it before the tools, so a screen reader reaches the card
                     name before its buttons. Both boxes are floated, so the
                     source order does not change the rendered layout. --}}
                @if(! $hasTabs(isset($tabsSlot)))
                    <{{ $titleTag }} class="{{ $makeCardTitleClass() }}">
                        @isset($icon)<i class="{{ $icon }} me-1" aria-hidden="true"></i>@endisset
                        @isset($titleSlot)
                            {{ $titleSlot }}
                        @else
                            @isset($title){{ $title }}@endisset
                        @endisset
                    </{{ $titleTag }}>
                @endif

                {{-- Tools (floated to the right). On a tabbed card they must
                     still come before the block level tabs navigation. --}}
                <div class="card-tools">

                    {{-- Extra tools slot --}}
                    @isset($toolsSlot)
                        {{ $toolsSlot }}
                    @endisset

                    {{-- Maximize tool --}}
                    @isset($maximizable)
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-maximize"
                            aria-label="{{ __('adminlte::adminlte.card_maximize') }}">
                            <i data-lte-icon="maximize" class="bi bi-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit"></i>
                        </button>
                    @endisset

                    {{-- Collapse tool (the icon visibility is handled by AdminLTE) --}}
                    @isset($collapsible)
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"
                            aria-expanded="{{ $isCardCollapsed() ? 'false' : 'true' }}"
                            aria-label="{{ __('adminlte::adminlte.card_collapse') }}">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                    @endisset

                    {{-- Remove tool --}}
                    @isset($removable)
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-remove"
                            aria-label="{{ __('adminlte::adminlte.card_remove') }}">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    @endisset

                </div>

                @if($hasTabs(isset($tabsSlot)))

                    {{-- Tabs navigation --}}
                    @isset($tabsSlot)
                        {{ $tabsSlot }}
                    @else
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach($tabs as $tab)
                                <li class="nav-item" role="presentation">
                                    <button class="{{ $makeTabLinkClass($tab) }}" data-bs-toggle="pill"
                                        data-bs-target="#{{ $tab['id'] }}" type="button" role="tab"
                                        aria-controls="{{ $tab['id'] }}"
                                        aria-selected="{{ $tab['active'] ? 'true' : 'false' }}">
                                        @isset($tab['icon'])<i class="{{ $tab['icon'] }} me-1" aria-hidden="true"></i>@endisset
                                        {{ $tab['label'] }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @endisset

                @endif

            @endisset

        </div>
    @endif

    {{-- Card body --}}
    @if(! $slot->isEmpty())
        <div class="{{ $makeCardBodyClass() }}"@if($disabled) inert @endif>
            @if($hasTabs(isset($tabsSlot)))
                <div class="tab-content">
                    {{ $slot }}
                </div>
            @else
                {{ $slot }}
            @endif
        </div>
    @endif

    {{-- Card footer --}}
    @isset($footerSlot)
        <div class="{{ $makeCardFooterClass() }}"@if($disabled) inert @endif>
            {{ $footerSlot }}
        </div>
    @endisset

    {{-- Card overlay (shown when the card is disabled) --}}
    @if($disabled)
        <div class="card-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-body bg-opacity-75 rounded"
            style="z-index:20;">
            <i class="bi bi-slash-circle fs-2 text-body-secondary" aria-hidden="true"></i>
            <span class="visually-hidden">{{ __('adminlte::adminlte.card_disabled') }}</span>
        </div>
    @endif

</div>

{{-- Lock the page scroll for a card initiated on maximized mode --}}

@if($isCardMaximized())
@once
@push('js')
<script>

    document.documentElement.classList.add('maximized-card');

</script>
@endpush
@endonce
@endif
