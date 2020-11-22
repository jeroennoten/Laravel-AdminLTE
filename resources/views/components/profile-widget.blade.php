<div {{ $attributes->merge(['class' => 'card card-widget widget-user']) }}>

    {{-- Header --}}
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
            <img class="img-circle elevation-2" src="{{ $img }}" alt="User avatar: {{ $name }}">
        @else
            <div class="img-circle elevation-2 d-flex bg-dark" style="width:90px;height:90px;">
                <i class="fas fa-3x fa-user text-silver m-auto"></i>
            </div>
        @endisset
    </div>

    {{-- Footer / Profile Items --}}
    <div class="{{ $makeFooterClass() }}">
        <div class="row">{{ $slot }}</div>
    </div>

</div>

{{-- CSS Styles Section --}}

@once
@push('css')
<style type="text/css">

    {{-- Remove the profile item borders on extra small devices --}}

    @media (max-width: 575.98px) {
        .card.card-widget.widget-user .adminlte-widget-item {
            border: none !important;
        }
    }

</style>
@endpush
@endonce
