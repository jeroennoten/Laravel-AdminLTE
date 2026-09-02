{{-- Content header (AdminLTE v4 markup, it is placed by the layout inside
     the '.app-content-header > .container-fluid' wrapper) --}}

<div {{ $attributes->merge(['class' => 'row']) }}>

    {{-- Title column --}}
    <div class="col-sm-6">

        @if($slot->isNotEmpty())
            {{ $slot }}
        @elseif(! empty($title))
            <h1 class="{{ $makeTitleClass() }}">{{ $title }}</h1>
        @endif

    </div>

    {{-- Breadcrumb column --}}
    @if(isset($breadcrumbSlot) || $hasBreadcrumbs())

        <div class="col-sm-6">

            @isset($breadcrumbSlot)
                {{ $breadcrumbSlot }}
            @else
                <nav aria-label="{{ __('adminlte::adminlte.breadcrumb') }}">
                    <ol class="breadcrumb float-sm-end">
                        @foreach($breadcrumbs as $item)
                            <li class="{{ $makeBreadcrumbItemClass($item) }}"
                                @if($item['active']) aria-current="page" @endif>
                                @if(isset($item['url']))
                                    <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                                @else
                                    {{ $item['label'] }}
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            @endisset

        </div>

    @endif

</div>
