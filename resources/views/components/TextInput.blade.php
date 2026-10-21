@props(['type' => 'text', 'name' => null, 'value' => null])

<input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" {{ $attributes->merge(['class' => 'form-input']) }} />
