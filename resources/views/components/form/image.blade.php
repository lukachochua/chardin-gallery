@props(['id'=>'default_id', 'name', 'label', 'currentImage' => null])

<div class="mb-4">
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>

    @if (isset($currentImage) && $currentImage)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $currentImage) }}" alt="Current Image" class="max-w-xs">
        </div>
    @endif

    <input type="file" id="{{ $id }}" name="{{ $name }}"
        class="mt-1 block w-full text-sm text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        {{ $attributes }}>

    @error($name)
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>
