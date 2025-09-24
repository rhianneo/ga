<div class="mb-3">
    <label for="{{ $name }}" class="block font-medium text-gray-700">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}"
        class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        @foreach ($options as $option)
            <option value="{{ $option }}" @if(isset($selected) && $selected == $option) selected @endif>
                {{ $option }}
            </option>
        @endforeach
    </select>
</div>
