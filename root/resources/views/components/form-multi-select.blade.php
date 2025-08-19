@props([
    'name',
    'options' => [],
    'selected' => [],
    'multiple' => false,
    'label' => null,
    'helpText' => null,
])
<div class="mb-4">
    <div class="flex gap-2 items-center">
    @if($label)
        <label for="{{ $name }}" class="block text-gray-700 font-bold mb-2">{{ $label }}</label>
    @endif

    @if($helpText)
        <p class="text-sm text-gray-500 mb-1">{{ $helpText }}</p>
    @endif
    </div>

    <select
        name="{{ $multiple ? $name.'[]' : $name }}"
        id="{{ $name }}"
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes->merge(['class' => 'border rounded px-2 py-1 w-full shadow focus:outline-none focus:shadow-outline']) }}
    >
        @foreach($options as $value => $optionLabel)
            <option value="{{ $value }}"
                @if($multiple && is_array($selected) && in_array($value, $selected)) selected @endif
                @if(!$multiple && $selected == $value) selected @endif
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
