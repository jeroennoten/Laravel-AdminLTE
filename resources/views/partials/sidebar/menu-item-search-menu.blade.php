@php
    // The search input needs an id, so the related label can target it.
    $searchInputId = $item['id'] ?? 'adminlte-sidebar-search-menu-input';

    // The menu that will be filtered by the AdminLTE SidebarSearch plugin.
    $searchTarget = '#'.($sidebarMenuId ?? 'adminlte-sidebar-menu');

    // The message to display when the filter matches no menu items.
    $searchEmptyText = $item['empty_text'] ?? __('adminlte::adminlte.no_matching_pages');
@endphp

<div class="sidebar-search" role="search">

    {{-- Search input (handled by the AdminLTE SidebarSearch plugin) --}}
    <label for="{{ $searchInputId }}" class="visually-hidden">{{ $item['text'] }}</label>

    <input class="form-control form-control-sm" type="search"
           id="{{ $searchInputId }}"
           placeholder="{{ $item['text'] }}"
           aria-label="{{ $item['text'] }}"
           autocomplete="off"
           data-lte-toggle="sidebar-search"
           data-lte-target="{{ $searchTarget }}"
           {!! $item['data-compiled'] ?? '' !!}>

    {{-- Empty results notice --}}
    <p class="fs-7 text-secondary mt-2 mb-0" data-lte-search-empty role="status" hidden>
        {{ $searchEmptyText }}
    </p>

</div>
