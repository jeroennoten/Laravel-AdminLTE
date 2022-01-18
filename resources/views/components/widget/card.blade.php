<div {{ $attributes->merge(['class' => $makeCardClass()]) }}>

    {{-- Card header --}}
    <div class="{{ $makeCardHeaderClass() }}">

        {{-- Title --}}
        <h3 class="{{ $makeCardTitleClass() }}">
            @isset($icon)<i class="{{ $icon }} mr-2"></i>@endisset
            @isset($title){{ $title }}@endisset
        </h3>

        {{-- Tools --}}
        <div class="card-tools">
            @isset($maximizable)
                <x-adminlte-button theme="tool" data-card-widget="maximize" icon="fas fa-lg fa-expand"/>
            @endisset

            @if($collapsible === 'collapsed')
                <x-adminlte-button theme="tool" data-card-widget="collapse" icon="fas fa-lg fa-plus"/>
            @elseif (isset($collapsible))
                <x-adminlte-button theme="tool" data-card-widget="collapse" icon="fas fa-lg fa-minus"/>
            @endif

            @isset($removable)
                <x-adminlte-button theme="tool" data-card-widget="remove" icon="fas fa-lg fa-times"/>
            @endisset
        </div>

    </div>

    {{-- Card body --}}
    @if(! $slot->isEmpty())
        <div {{ $attributes->merge(['class' => $makeCardBodyClass()]) }}>
            {{ $slot }}
        </div>
    @endif

    {{-- Card overlay --}}
    @if($disabled)
        <div class="overlay">
            <i class="fas fa-2x fa-ban text-gray"></i>
        </div>
    @endif

</div>
