<div class="filter d-flex {{$topclass}}">
    <div class="form-group mr-1">
        <div class="input-group">
            <button type="button" class="btn btn-default float-right {{$inputclass}}" id="{{$id}}">
                <i class="{{$icon}}"></i> {{$title}}
                <i class="fas fa-caret-down"></i>
            </button>
        </div>
    </div>
    <div class="form-group ml-1" style="flex: 1 0 auto">
        <input type="text" class="form-control" id="range-result" style="border:0;background:transparent" disabled>
    </div>
</div>

@section('js')
@parent 
<script>
    $('#{{$id}}').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        dateLimit: {
            months: 12
        },
        {!!$initiator!!}
    }, function (start, end) {
        $('#range-result').val('Showing results: from [' + start.format('YYYY-MM-DD') + '] to [' + end.format('YYYY-MM-DD') + ']');
        {!!$callback!!}
    });
</script>
@endsection