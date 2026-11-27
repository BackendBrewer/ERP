<div class="field">
    <label for="{{ $key }}">
        {{ $label }}
        @if ($required)<span class="required-mark" aria-hidden="true">*</span>@endif
    </label>
    <input id="{{ $key }}" name="{{ $key }}" type="{{ $type }}" value="{{ old($key, $value) }}"
           @if ($length) maxlength="{{ $length }}" @endif
           @if ($required) required @endif
           @error($key) aria-invalid="true" aria-describedby="{{ $key }}-error" @enderror>
    @error($key)
        <p class="field-error" id="{{ $key }}-error" role="alert">{{ $message }}</p>
    @enderror
</div>
