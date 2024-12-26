@props([
    'name',
    'id' => null,
    'label',
    'options' => [],
    'selected' => null,
    'multiple' => false,
    'attributes' => [],
])

<div class="mb-4">
    <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>

    <select name="{{ $name }}{{ $multiple ? '[]' : '' }}" id="{{ $id ?? $name }}"
        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        {{ $multiple ? 'multiple' : '' }} {{ $attributes }}>
        @foreach ($options as $key => $value)
            <option value="{{ $key }}" {{ in_array($key, old($name, (array) $selected)) ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>
