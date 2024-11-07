{{-- Table --}}

<div class="table-responsive">

<table id="{{ $id }}" style="width:100%" {{ $attributes->merge(['class' => $makeTableClass()]) }}>

    {{-- Table head --}}
    <thead @isset($headTheme) class="thead-{{ $headTheme }}" @endisset>
        <tr>
            @foreach($heads as $th)
                <th @isset($th['classes']) class="{{ $th['classes'] }}" @endisset
                    @isset($th['width']) style="width:{{ $th['width'] }}%" @endisset
                    @isset($th['no-export']) dt-no-export @endisset>
                    {{ is_array($th) ? ($th['label'] ?? '') : $th }}
                </th>
            @endforeach
        </tr>
    </thead>

    {{-- Table body --}}
    <tbody>{{ $slot }}</tbody>

    {{-- Table footer --}}
    @isset($withFooter)
        <tfoot @isset($footerTheme) class="thead-{{ $footerTheme }}" @endisset>
            <tr>
                @foreach($heads as $th)
                    <th>{{ is_array($th) ? ($th['label'] ?? '') : $th }}</th>
                @endforeach
            </tr>
        </tfoot>
    @endisset

</table>

</div>

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {
        $('#{{ $id }}').DataTable( @json($config) );
    })

</script>
@endpush

{{-- Add CSS styling for beautify option --}}

@isset($beautify)
    @push('css')
    <style type="text/css">
        #{{ $id }} tr td,  #{{ $id }} tr th {
            vertical-align: middle;
            text-align: center;
        }
    </style>
    @endpush
@endisset

{{-- Improve CSS styling when using responsive extension --}}

@if(! empty($config['responsive']))
    @once
    @push('css')
    <style type="text/css">
        .dataTable .child .dtr-details {
            width: 100%;
        }
        .dataTable .child .dtr-data {
            float: right;
        }
    </style>
    @endpush
    @endonce
@endif
