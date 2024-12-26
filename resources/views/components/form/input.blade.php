@props([
    'name',
    'id' => null,
    'label',
    'value' => '',
    'type' => 'text',
    'required' => false,
    'step' => null,
    'attributes' => [],
])

<div class="mb-4">
    <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>

    @if ($type == 'textarea')
        <textarea id="{{ $id ?? $name }}" name="{{ $name }}"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @elseif($type == 'file')
        <input type="file" id="{{ $id ?? $name }}" name="{{ $name }}"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            {{ $attributes }}>
    @elseif($type == 'checkbox')
        <input type="hidden" name="{{ $name }}" value="0">
        <input type="checkbox" id="{{ $id ?? $name }}" name="{{ $name }}" value="1"
            {{ old($name, $value) ? 'checked' : '' }}
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    @else
        <input type="{{ $type }}" id="{{ $id ?? $name }}" name="{{ $name }}"
            value="{{ old($name, $value) }}"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            {{ $attributes }}>
    @endif

    @error($name)
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>
