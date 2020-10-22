<li @if(isset($item['id'])) id="{{ $item['id'] }}" @endif>

    <form action="{{ $item['href'] }}" method="{{ $item['method'] }}" class="form-inline">
        {{ csrf_field() }}
        
        <div class="input-group">
            <input class="form-control form-control-sidebar" type="search"
                   name="{{ $item['input_name'] }}" placeholder="{{ $item['text'] }}"
                   aria-label="{{ $item['aria-label'] ?? $item['text'] }}">
            <div class="input-group-append">
                <button class="btn btn-sidebar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

</li>
