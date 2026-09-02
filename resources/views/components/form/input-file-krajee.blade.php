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
        <label for="{{ $id }}" class="{{ $makeLabelClass() }}">
            {{ $label }}
        </label>
    @endisset

    {{-- Krajee file input --}}
    <input type="file" id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge($makeItemAttributes()) }}>

    {{-- Error feedback --}}
    @if($isInvalid())
        <span class="{{ $makeInvalidFeedbackClass() }}" role="alert">
            <strong>{{ $errors->first($errorKey) }}</strong>
        </span>
    @endif

</div>

{{-- Add the plugin initialization code --}}
{{-- NOTE: the Krajee file input plugin still requires jQuery --}}

@push('js')
<script>

    if (window.jQuery) {

        window.jQuery(function ($) {

            if (typeof $.fn.fileinput === 'undefined') {
                return;
            }

            // Initialize the plugin.

            $('#{{ $id }}').fileinput( @json((object) $config) );

            // Workaround to force setup of invalid class.

            @if($isInvalid())
                $('#{{ $id }}').closest('.file-input')
                    .find('.file-caption-name')
                    .addClass('is-invalid');

                $('#{{ $id }}').closest('.file-input')
                    .find('.file-preview')
                    .addClass('adminlte-invalid-krajee-preview');
            @endif

            // Make custom style for particular scenarios (modes).

            @if($presetMode == 'avatar')
                $('#{{ $id }}').closest('.file-input')
                    .addClass('text-center')
                    .find('.file-drop-zone')
                    .addClass('border-0');
            @endif
        });
    }

</script>
@endpush

{{-- Setup the custom invalid style for the plugin --}}

@once
@push('css')
<style type="text/css">

    .adminlte-invalid-krajee-preview {
        box-shadow: 0 .15rem 0.25rem rgba(var(--bs-danger-rgb, 220, 53, 69), .25);
    }

</style>
@endpush
@endonce
