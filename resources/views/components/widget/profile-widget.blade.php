<div {{ $attributes->merge(['class' => $makeCardClass()]) }}>

    {{-- Profile header --}}
    <div class="{{ $makeHeaderClass() }}" style="{{ $makeHeaderStyle() }}">

        {{-- User image --}}
        <div class="widget-user-image">
            @if(isset($img))
                <img class="img-circle elevation-2" src="{{ $img }}" alt="User avatar: {{ $name }}">
            @elseif($layoutType === 'modern')
                <div class="img-circle elevation-2 d-flex bg-dark" style="width:90px;height:90px;">
                    <i class="fas fa-3x fa-user text-silver m-auto"></i>
                </div>
            @elseif($layoutType === 'classic')
                <div class="img-circle elevation-2 float-left d-flex bg-dark" style="width:65px;height:65px;">
                    <i class="fas fa-2x fa-user text-silver m-auto"></i>
                </div>
            @endisset
        </div>

        {{-- User name --}}
        @isset($name)
            <h3 class="widget-user-username mb-0">{{ $name }}</h3>
        @endisset

        {{-- User description --}}
        @isset($desc)
            <h5 class="widget-user-desc">{{ $desc }}</h5>
        @endisset

    </div>

    {{-- Profile footer / Profile Items --}}
    @if(! $slot->isEmpty())
        <div class="{{ $makeFooterClass() }}">
            <div class="row">{{ $slot }}</div>
        </div>
    @endif

</div>
