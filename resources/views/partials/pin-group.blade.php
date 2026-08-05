@php
    $value = in_array($name, ['mpin', 'mpin_confirmation', 'current_mpin'], true) ? '' : old($name, $value ?? '');
@endphp
<div class="pin-group" data-pin-group data-auto-submit="{{ ! empty($autoSubmit) ? '1' : '0' }}">
    <label class="field-label" for="{{ $groupId }}-0">{{ $label }}</label>
    <input type="hidden" name="{{ $name }}" value="{{ $value }}" data-pin-target>
    <div class="pin-boxes">
        @for($i = 0; $i < 4; $i++)
            <input
                id="{{ $groupId }}-{{ $i }}"
                class="pin-box"
                type="text"
                inputmode="numeric"
                maxlength="1"
                autocomplete="one-time-code"
                data-pin-box
                value="{{ $value !== '' ? substr($value, $i, 1) : '' }}"
            >
        @endfor
    </div>
</div>
