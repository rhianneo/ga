<div class="mb-3">
    <label for="{{ $name }}" class="block font-medium text-gray-700">{{ $label }}</label>
    <input type="{{ $type ?? 'text' }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value ?? '') }}"
        class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
</div>
