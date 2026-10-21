@props(['options' => [], 'name' => null])

<select name="{{ $name }}" {{ $attributes->merge(['class' => 'form-select']) }}>
    @foreach($options as $option)
        <option value="{{ $option['value'] ?? $option['id'] ?? $option }}">{{ $option['label'] ?? $option['text'] ?? $option }}</option>
    @endforeach
</select>
