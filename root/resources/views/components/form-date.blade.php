@props([
    'name',
    'label' => null,
    'value' => old($name, ''),
    'required' => false,
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-gray-700 font-bold mb-2">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <input
        type="date"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        {{ $required ? 'required' : '' }}
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
    >
    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
