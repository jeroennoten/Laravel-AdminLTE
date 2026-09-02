{{-- Table --}}

<div {{ $makeWrapperAttributes()->merge(['class' => $makeWrapperClass()]) }}>

<table id="{{ $id }}" {{ $attributes->merge([
    'class' => $makeTableClass(),
    'style' => 'width:100%',
]) }}>

    {{-- Table head --}}
    <thead @isset($headTheme) class="table-{{ $headTheme }}" @endisset>
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
        <tfoot @isset($footerTheme) class="table-{{ $footerTheme }}" @endisset>
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

    // The DataTables plugin still requires jQuery, so the initialization is
    // guarded in order to not break a jQuery free application. Note the
    // vanilla javascript alternative recommended by AdminLTE v4 is Tabulator.

    window._AdminLTE_Ready(() => {

        if (typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.DataTable === 'undefined') {
            console.warn('The datatable component requires the jQuery based Datatables plugin.');

            return;
        }

        window.jQuery('#{{ $id }}').DataTable(@json($config));
    });

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
            float: inline-end;
        }
    </style>
    @endpush
    @endonce
@endif
