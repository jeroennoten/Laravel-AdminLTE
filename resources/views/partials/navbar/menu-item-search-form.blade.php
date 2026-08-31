{{-- Navbar search form (AdminLTE v4 markup) --}}

@php($searchId = $item['id'] ?? 'navbar-search-input')
@php($searchLabel = Lang::has('adminlte::adminlte.search') ? __('adminlte::adminlte.search') : 'Search')

<li class="nav-item d-none d-md-flex align-items-center">

    <form class="navbar-search ms-md-3" role="search" action="{{ $item['href'] }}"
          method="{{ $item['method'] }}">

        @if(strtolower($item['method']) === 'post')
            {{ csrf_field() }}
        @endif

        {{-- Search label (only visible for screen readers) --}}
        <label for="{{ $searchId }}" class="visually-hidden">
            {{ $item['text'] ?? $searchLabel }}
        </label>

        <div class="navbar-search-field">

            {{-- Search input --}}
            <input type="search" class="form-control" id="{{ $searchId }}"
                   name="{{ $item['input_name'] }}"
                   placeholder="{{ $item['text'] }}"
                   aria-label="{{ $item['text'] }}"
                   autocomplete="off">

            {{-- Search button --}}
            <button class="navbar-search-submit" type="submit"
                    aria-label="{{ $searchLabel }}">
                <i class="bi bi-search" aria-hidden="true"></i>
            </button>

        </div>

    </form>

</li>

{{-- On small screens the field above is hidden, so link to the search page --}}
<li class="nav-item d-md-none">
    <a class="nav-link" href="{{ $item['href'] }}" aria-label="{{ $searchLabel }}">
        <i class="bi bi-search" aria-hidden="true"></i>
    </a>
</li>
