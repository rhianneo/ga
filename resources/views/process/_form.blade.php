@php
    $appTypes = ['New Application', 'Renewal Application', 'Cancellation and Downgrading'];
@endphp

<div class="space-y-4">

    {{-- Application Type --}}
    <div>
        <label class="block font-medium mb-1">Application Type</label>
        <select name="application_type" class="w-full border-gray-300 rounded-md" required {{ $process ? 'disabled' : '' }}>
            @foreach ($appTypes as $typeOption)
                <option value="{{ $typeOption }}" {{ ($process->application_type ?? $type) === $typeOption ? 'selected' : '' }}>{{ $typeOption }}</option>
            @endforeach
        </select>
    </div>

    {{-- Major Process --}}
    <div>
        <label class="block font-medium mb-1">Major Process</label>
        <input type="text" name="major_process" value="{{ old('major_process', $process->major_process ?? '') }}" class="w-full border-gray-300 rounded-md" required>
    </div>

    {{-- Sub Process --}}
    <div>
        <label class="block font-medium mb-1">Sub Process (optional)</label>
        <textarea name="sub_process" class="w-full border-gray-300 rounded-md">{{ old('sub_process', $process->sub_process ?? '') }}</textarea>
    </div>

    {{-- Order --}}
    <div>
        <label class="block font-medium mb-1">Order</label>
        <input type="number" name="order" value="{{ old('order', $process->order ?? 1) }}" class="w-full border-gray-300 rounded-md" required>
    </div>

    {{-- Duration --}}
    <div>
        <label class="block font-medium mb-1">Duration (days, optional)</label>
        <input type="number" name="duration_days" value="{{ old('duration_days', $process->duration_days ?? '') }}" class="w-full border-gray-300 rounded-md">
    </div>

</div>
