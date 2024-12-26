@props(['name', 'label', 'value' => 0])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" id="{{ $name }}" value="1"
        {{ old($name, $value) == 1 ? 'checked' : '' }}
        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

    @error($name)
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>
