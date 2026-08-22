<div {{ $attributes->merge(['class' => $makeCardClass()]) }}>

    @if($layoutType === 'classic')

        {{-- Profile header (widget-user-2 layout) --}}
        <div class="{{ $makeHeaderClass() }}" style="{{ $makeHeaderStyle() }}">

            {{-- User image --}}
            <div class="widget-user-image">
                @isset($img)
                    <img class="rounded-circle shadow" src="{{ $img }}" alt="{{ $name }}">
                @else
                    <div class="rounded-circle shadow d-flex align-items-center justify-content-center bg-body-secondary text-body-secondary"
                        style="width:4.0625rem;height:4.0625rem;">
                        <i class="{{ $icon }} fs-2" aria-hidden="true"></i>
                    </div>
                @endisset
            </div>

            {{-- User name and description --}}
            <div>
                @isset($name)
                    <h3 class="widget-user-username">{{ $name }}</h3>
                @endisset

                @isset($desc)
                    <h5 class="widget-user-desc">{{ $desc }}</h5>
                @endisset
            </div>

        </div>

    @else

        {{-- Profile header (widget-user layout) --}}
        <div class="{{ $makeHeaderClass() }}" style="{{ $makeHeaderStyle() }}">

            {{-- User name --}}
            @isset($name)
                <h3 class="widget-user-username">{{ $name }}</h3>
            @endisset

            {{-- User description --}}
            @isset($desc)
                <h5 class="widget-user-desc">{{ $desc }}</h5>
            @endisset

        </div>

        {{-- User image --}}
        <div class="widget-user-image">
            @isset($img)
                <img class="rounded-circle shadow" src="{{ $img }}" alt="{{ $name }}">
            @else
                <div class="rounded-circle shadow d-flex align-items-center justify-content-center bg-body-secondary text-body-secondary"
                    style="width:100%;aspect-ratio:1;border:3px solid var(--bs-body-bg);">
                    <i class="{{ $icon }} fs-1" aria-hidden="true"></i>
                </div>
            @endisset
        </div>

    @endif

    {{-- Profile footer / Profile Items. Note the footer is always rendered,
         since it reserves the space of the (absolutely positioned) user image
         on the AdminLTE v4 widget. --}}
    <div class="{{ $makeFooterClass() }}">
        <div class="row">{{ $slot }}</div>
    </div>

</div>
