{{--
Note: we don't extends the 'input-group-component' blade layout as we have done
with other form components. The reason is that the underlying Krajee file input
plugin already generates an 'input-group' structure and will conflict with the
one provided by the mentioned layout. So instead, we define a new layout.
--}}

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Create the form group layout --}}

<div class="{{ $makeFormGroupClass() }}">

    {{-- Input label --}}
    @isset($label)
        <label for="{{ $id }}" @isset($labelClass) class="{{ $labelClass }}" @endisset>
            {{ $label }}
        </label>
    @endisset

    {{-- Krajee file input --}}
    <input type="file" id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}>

    {{-- Error feedback --}}
    @if($isInvalid())
        <span class="{{ $makeInvalidFeedbackClass() }}" role="alert">
            <strong>{{ $errors->first($errorKey) }}</strong>
        </span>
    @endif

</div>

{{-- Add the plugin initialization code --}}

@push('js')
<script>

    $(() => {

        // Initialize the plugin.

        $('#{{ $id }}').fileinput( @json($config) );

        // Workaround to force setup of invalid class.

        @if($isInvalid())
            $('#{{ $id }}').closest('.file-input')
                .find('.file-caption-name')
                .addClass('is-invalid')

            $('#{{ $id }}').closest('.file-input')
                .find('.file-preview')
                .css('box-shadow', '0 .15rem 0.25rem rgba(255,0,0,.25)');
        @endif

        // Make custom style for particular scenarios (modes).

        @if($presetMode == 'avatar')
            $('#{{ $id }}').closest('.file-input')
                .addClass('text-center')
                .find('.file-drop-zone')
                .addClass('border-0');
        @endif
    })

</script>
@endpush
