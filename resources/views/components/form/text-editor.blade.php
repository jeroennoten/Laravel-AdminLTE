@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- Hidden textarea holding the value submitted with the form --}}
    <textarea id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge($makeItemAttributes()) }}
    >{{ $getOldValue($errorKey, $slot) }}</textarea>

    {{-- The wrapper of the 'Quill' editor. The plugin injects its toolbar as
         a sibling of the target element, so the wrapper keeps both the
         toolbar and the editing area inside a single input group item. --}}
    <div class="{{ $makeEditorClass() }}">
        <div id="{{ $makeEditorId() }}"></div>
    </div>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    window._AdminLTE_Ready(function () {

        const source = document.getElementById(@json($id));
        const target = document.getElementById(@json($makeEditorId()));

        if (! source || ! target || typeof window.Quill === 'undefined') {
            return;
        }

        const usrCfg = @json((object) $makePluginConfig());

        {{-- Check for the placeholder attribute. --}}

        @if($attributes->has('placeholder'))
            usrCfg.placeholder = usrCfg.placeholder
                || @json($attributes->get('placeholder'));
        @endif

        {{-- Check for the disabled and readonly attributes. --}}

        @if($attributes->has('disabled') || $attributes->has('readonly'))
            usrCfg.readOnly = true;
        @endif

        const editor = new window.Quill(target, usrCfg);

        const readEditor = function () {
            return typeof editor.getSemanticHTML === 'function'
                ? editor.getSemanticHTML()
                : editor.root.innerHTML;
        };

        {{-- Load the initial content from the underlying textarea. Note the
             textarea content is html escaped on the server side, so the
             editor is the only place where it gets interpreted as html. --}}

        if (source.value) {
            editor.clipboard.dangerouslyPasteHTML(source.value);
        }

        {{-- Keep the underlying textarea in sync with the editor. --}}

        editor.on('text-change', function () {
            source.value = readEditor();
        });

        {{-- Submit the latest content even when the editor was never
             focused nor modified. --}}

        const form = source.closest('form');

        if (form) {
            form.addEventListener('submit', function () {
                source.value = readEditor();
            });
        }
    });

</script>
@endpush

{{-- Setup the height of this particular editor --}}

@if($makeEditorHeight())
@push('css')
<style type="text/css">

    #{{ $makeEditorId() }} .ql-editor {
        min-height: {{ $makeEditorHeight() }};
    }

</style>
@endpush
@endif

{{-- Setup the base styles and the custom invalid style for the editor --}}

@once
@push('css')
<style type="text/css">

    .adminlte-text-editor {
        flex: 1 1 auto;
        height: auto;
        overflow: hidden;
    }

    .adminlte-text-editor .ql-editor {
        min-height: 10rem;
    }

    .adminlte-text-editor .ql-toolbar {
        border: 0;
        border-bottom: var(--bs-border-width, 1px) solid var(--bs-border-color, #dee2e6);
    }

    .adminlte-text-editor .ql-container {
        border: 0;
        font-family: inherit;
        font-size: inherit;
    }

    {{-- SM size setup --}}
    .input-group-sm .adminlte-text-editor {
        font-size: .875rem;
        line-height: 1;
    }

    {{-- LG size setup --}}
    .input-group-lg .adminlte-text-editor {
        font-size: 1.25rem;
        line-height: 1.5;
    }

    {{-- Custom invalid style setup --}}

    .adminlte-invalid-itegroup .adminlte-text-editor {
        box-shadow: 0 .25rem 0.5rem rgba(var(--bs-danger-rgb, 220, 53, 69), .25);
        border-color: var(--bs-danger, #dc3545) !important;
    }

</style>
@endpush
@endonce
