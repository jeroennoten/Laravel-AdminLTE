{{-- Navbar custom menu (AdminLTE v4) --}}

<div {{ $attributes->merge(['class' => $makeWrapperClass()]) }}>

    <ul class="{{ $makeNavClass() }}">
        {{ $slot }}
    </ul>

</div>
