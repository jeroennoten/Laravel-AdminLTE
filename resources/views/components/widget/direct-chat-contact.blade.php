<li {{ $attributes->merge() }}>

    @isset($url)<a href="{{ $url }}">@endisset

        {{-- Contact avatar --}}
        @isset($img)
            <img class="contacts-list-img" src="{{ $img }}" alt="{{ $name }}">
        @endisset

        <div class="contacts-list-info">

            {{-- Contact name and date of the last message --}}
            <span class="contacts-list-name">
                @isset($name){{ $name }}@endisset
                @isset($date)<small class="contacts-list-date float-end">{{ $date }}</small>@endisset
            </span>

            {{-- Excerpt of the last message --}}
            @if($hasMsg(! $slot->isEmpty()))
                <span class="contacts-list-msg">
                    @if(! $slot->isEmpty())
                        {{ $slot }}
                    @else
                        {{ $msg }}
                    @endif
                </span>
            @endif

        </div>

    @isset($url)</a>@endisset

</li>
