<div class="modal fade" id="{{$id}}" style="z-index:{{$zindex}}">
    <div class="modal-dialog {{$centered ? 'modal-dialog-centered' : ''}} {{$modalsize}}">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{$title}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{$slot}}
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>