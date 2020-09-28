@if($buttons)
    @section('css')
    @parent 
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.min.css" />
    <style>.printer table{counter-reset: rowNumber}.printer tr{counter-increment: rowNumber}.printer tr td:first-child::before{content: counter(rowNumber);min-width: 1em;margin-right: 0.5em} div.dt-buttons.btn-group.flex-wrap {float:right}</style>
    @endsection

    @section('js')
    @parent 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.min.js"></script>
    @endsection
@endif

@if($beautify)
<style>#{{$id}} tbody tr td { vertical-align: middle; text-align: center; }</style>
@endif  
<table id="{{$id}}" class="table {{$border}} {{$hover}}">
    <thead>
        <tr>
            @foreach($heads as $th)
            @if(isset($th->name))
            <td style="width:{{$th->width}}%">{{$th->name}}</td>
            @else
            <td>{{$th}}</td>
            @endif
            @endforeach
        </tr>
    </thead>
    <tbody>

    </tbody>

    @if($footer)
    <tfoot>
        <tr>
            @foreach($heads as $foot)
            <th></th>
            @endforeach
        </tr>
    </tfoot>
    @endif
</table>