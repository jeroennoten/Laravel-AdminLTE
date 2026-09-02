<div {{ $attributes->merge(['class' => $makeCardClass()]) }}>

    {{-- Card header --}}
    @if(! $isCardHeaderEmpty(isset($toolsSlot), isset($contactsSlot)))
        <div class="{{ $makeCardHeaderClass() }}">

            {{-- Title. The reference (dist/index.html) puts it before the
                 tools, so a screen reader reaches the chat name before its
                 buttons. Both boxes are floated, so the source order does not
                 change the rendered layout. --}}
            <h3 class="card-title">
                @isset($icon)<i class="{{ $icon }} me-1" aria-hidden="true"></i>@endisset
                @isset($title){{ $title }}@endisset
            </h3>

            {{-- Tools (floated to the right) --}}
            <div class="card-tools">

                {{-- Unread messages badge --}}
                @if($hasBadge())
                    <span class="{{ $makeBadgeClass() }}"
                        title="{{ __('adminlte::adminlte.direct_chat_new_messages') }}">{{ $badge }}</span>
                @endif

                {{-- Extra tools slot --}}
                @isset($toolsSlot)
                    {{ $toolsSlot }}
                @endisset

                {{-- Maximize tool --}}
                @isset($maximizable)
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-maximize"
                        aria-label="{{ __('adminlte::adminlte.card_maximize') }}">
                        <i data-lte-icon="maximize" class="bi bi-fullscreen"></i>
                        <i data-lte-icon="minimize" class="bi bi-fullscreen-exit"></i>
                    </button>
                @endisset

                {{-- Collapse tool (the icon visibility is handled by AdminLTE) --}}
                @isset($collapsible)
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"
                        aria-expanded="{{ $isCardCollapsed() ? 'false' : 'true' }}"
                        aria-label="{{ __('adminlte::adminlte.card_collapse') }}">
                        <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                        <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                    </button>
                @endisset

                {{-- Contacts pane tool (the sliding is handled by AdminLTE) --}}
                @isset($contactsSlot)
                    <button type="button" class="btn btn-tool" data-lte-toggle="chat-pane"
                        aria-expanded="{{ $contactsOpen ? 'true' : 'false' }}"
                        title="{{ __('adminlte::adminlte.direct_chat_contacts') }}"
                        aria-label="{{ __('adminlte::adminlte.direct_chat_contacts') }}">
                        <i class="bi bi-chat-text-fill"></i>
                    </button>
                @endisset

                {{-- Remove tool --}}
                @isset($removable)
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-remove"
                        aria-label="{{ __('adminlte::adminlte.card_remove') }}">
                        <i class="bi bi-x-lg"></i>
                    </button>
                @endisset

            </div>

        </div>
    @endif

    {{-- Card body (holds both sliding panes) --}}
    <div class="{{ $makeCardBodyClass() }}">

        {{-- Messages pane --}}
        <div class="direct-chat-messages" @if($height) style="{{ $makePaneStyle() }}" @endif
            role="log" tabindex="0" aria-label="{{ __('adminlte::adminlte.direct_chat_messages') }}">
            {{ $slot }}
        </div>

        {{-- Contacts pane --}}
        @isset($contactsSlot)
            <div class="{{ $makeContactsClass() }}" @if($height) style="{{ $makePaneStyle() }}" @endif
                aria-label="{{ __('adminlte::adminlte.direct_chat_contacts') }}">
                <ul class="contacts-list">
                    {{ $contactsSlot }}
                </ul>
            </div>
        @endisset

    </div>

    {{-- Card footer --}}
    @isset($footerSlot)
        <div class="{{ $makeCardFooterClass() }}">
            {{ $footerSlot }}
        </div>
    @endisset

</div>
