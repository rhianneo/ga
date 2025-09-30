@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Create Application</h1>

        <form action="{{ route('applications.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block font-semibold text-gray-700">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Application Type -->
            <div>
                <label for="type" class="block font-semibold text-gray-700">Application Type <span class="text-red-500">*</span></label>
                <select name="type" id="type"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="New Application" {{ old('type') == 'New Application' ? 'selected' : '' }}>New Application</option>
                    <option value="Renewal Application" {{ old('type') == 'Renewal Application' ? 'selected' : '' }}>Renewal Application</option>
                    <option value="Cancellation and Downgrading" {{ old('type') == 'Cancellation and Downgrading' ? 'selected' : '' }}>Cancellation and Downgrading</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Factory -->
            <div>
                <label for="factory" class="block font-semibold text-gray-700">Factory <span class="text-red-500">*</span></label>
                <select name="factory" id="factory"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="Device Factory" {{ old('factory') == 'Device Factory' ? 'selected' : '' }}>Device Factory</option>
                    <option value="Medical Factory" {{ old('factory') == 'Medical Factory' ? 'selected' : '' }}>Medical Factory</option>
                </select>
                @error('factory')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Position -->
            <div>
                <label for="position" class="block font-semibold text-gray-700">Position</label>
                <input type="text" name="position" id="position" value="{{ old('position') }}"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('position')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Passport Number -->
            <div>
                <label for="passport_number" class="block font-semibold text-gray-700">Passport Number</label>
                <input type="text" name="passport_number" id="passport_number" value="{{ old('passport_number') }}"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('passport_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- TIN -->
            <div>
                <label for="TIN" class="block font-semibold text-gray-700">TIN</label>
                <input type="text" name="TIN" id="TIN" value="{{ old('TIN') }}"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('TIN')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- AEP Number -->
            <div>
                <label for="AEP_number" class="block font-semibold text-gray-700">AEP Number</label>
                <input type="text" name="AEP_number" id="AEP_number" value="{{ old('AEP_number') }}"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('AEP_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Expiry Date (Optional) -->
            <div>
                <label for="expiry_date" class="block font-semibold text-gray-700">Expiry Date</label>
                <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('expiry_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Optional</p>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block font-semibold text-gray-700">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="Not Started" {{ old('status') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                    <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between mt-6">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Create Application</button>
                <a href="{{ route('applications.index') }}"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
