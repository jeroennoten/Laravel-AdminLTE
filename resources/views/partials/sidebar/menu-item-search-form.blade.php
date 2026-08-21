@php
    // The search input needs an id, so the related label can target it.
    $searchInputId = $item['id'] ?? 'adminlte-sidebar-search-form-input';
@endphp

<div class="sidebar-search" role="search">

    <form action="{{ $item['href'] }}" method="{{ $item['method'] }}">

        @if(strtolower($item['method']) === 'post')
            {{ csrf_field() }}
        @endif

        {{-- Search input --}}
        <label for="{{ $searchInputId }}" class="visually-hidden">{{ $item['text'] }}</label>

        <input class="form-control form-control-sm" type="search"
               id="{{ $searchInputId }}"
               name="{{ $item['input_name'] }}"
               placeholder="{{ $item['text'] }}"
               aria-label="{{ $item['text'] }}"
               autocomplete="off">

        {{-- Search submit (visually hidden, the input already submits on enter) --}}
        <button type="submit" class="visually-hidden">{{ $item['text'] }}</button>

    </form>

</div>
