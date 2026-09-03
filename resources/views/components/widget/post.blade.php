<div {{ $attributes->merge(['class' => 'post']) }}>

    {{-- Post author (an AdminLTE user block) --}}
    @if($hasAuthor())
        <x-adminlte-user-block :name="$name" :img="$img" :description="$description"
            :url="$url" :size="$size"/>
    @endif

    {{-- Post content --}}
    {{ $slot }}

    {{-- Post footer, the actions row of the reference feed entry --}}
    @isset($footerSlot)
        <p class="mb-0">{{ $footerSlot }}</p>
    @endisset

</div>
