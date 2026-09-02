<div {{ $attributes->merge(['class' => $makeUserBlockClass()]) }}>

    {{-- User image --}}
    @isset($img)
        <img class="rounded-circle" src="{{ $img }}" alt="{{ $name }}">
    @endisset

    {{-- User name --}}
    @isset($name)
        <span class="username">

            @isset($url)
                <a href="{{ $url }}">{{ $name }}</a>
            @else
                {{ $name }}
            @endisset

        </span>
    @endisset

    {{-- User short description --}}
    @isset($description)
        <span class="description">{{ $description }}</span>
    @endisset

    {{-- User comment/content --}}
    @if(! $slot->isEmpty())
        <span class="comment">{{ $slot }}</span>
    @endif

</div>
