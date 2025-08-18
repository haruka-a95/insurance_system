<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 font-bold mb-2">
        {{ $label }}
    </label>
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
    >
        <option value="">選択してください</option>
        @foreach ($options as $value => $labelOption)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                {{ $labelOption }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
    @enderror
</div>
