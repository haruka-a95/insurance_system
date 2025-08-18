<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 font-bold mb-2">
        {{ $label }}
    </label>
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
    @enderror
</div>
