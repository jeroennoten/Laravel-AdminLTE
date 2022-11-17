<div {{ $attributes->merge(['class' => $makeCardClass()]) }}>

    {{-- Card header --}}
    @if(! $isCardHeaderEmpty(isset($toolsSlot)))
        <div class="{{ $makeCardHeaderClass() }}">

            {{-- Title --}}
            <h3 class="{{ $makeCardTitleClass() }}">
                @isset($icon)<i class="{{ $icon }} mr-1"></i>@endisset
                @isset($title){{ $title }}@endisset
            </h3>

            {{-- Tools --}}
            <div class="card-tools">

                {{-- Extra tools slot --}}
                @isset($toolsSlot)
                    {{ $toolsSlot }}
                @endisset

                {{-- Default tools --}}
                @isset($maximizable)
                    <x-adminlte-button theme="tool" data-card-widget="maximize" icon="fas fa-lg fa-expand"/>
                @endisset

                @if($collapsible === 'collapsed')
                    <x-adminlte-button theme="tool" data-card-widget="collapse" icon="fas fa-lg fa-plus"/>
                @elseif(isset($collapsible))
                    <x-adminlte-button theme="tool" data-card-widget="collapse" icon="fas fa-lg fa-minus"/>
                @endif

                @isset($removable)
                    <x-adminlte-button theme="tool" data-card-widget="remove" icon="fas fa-lg fa-times"/>
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

    {{-- Card overlay --}}
    @if($disabled)
        <div class="overlay">
            <i class="fas fa-2x fa-ban text-gray"></i>
        </div>
    @endif

</div>
