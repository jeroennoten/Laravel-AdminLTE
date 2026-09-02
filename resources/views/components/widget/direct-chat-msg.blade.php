<div {{ $attributes->merge(['class' => $makeMsgClass()]) }}>

    {{-- Author and timestamp (they swap their sides on an outgoing message) --}}
    @if(! $isInfosEmpty())
        <div class="direct-chat-infos clearfix">

            @isset($name)
                <span class="{{ $makeNameClass() }}">{{ $name }}</span>
            @endisset

            @isset($timestamp)
                <span class="{{ $makeTimestampClass() }}">{{ $timestamp }}</span>
            @endisset

        </div>
    @endif

    {{-- Author avatar --}}
    @isset($img)
        <img class="direct-chat-img" src="{{ $img }}" alt="{{ $name }}">
    @endisset

    {{-- Message bubble --}}
    <div class="direct-chat-text">{{ $slot }}</div>

</div>
