@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4 text-center">Edit Application for {{ $application->name }}</h1>

        <form action="{{ route('applications.update', $application->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Name (Non-editable) -->
            <div>
                <label for="name" class="block font-semibold">Name</label>
                <input type="text" id="name" name="name" value="{{ $application->name }}" class="w-full p-2 border border-gray-300 rounded" readonly>
            </div>

            <!-- Application Type (Editable) -->
            <div>
                <label for="type" class="block font-semibold">Application Type</label>
                <select name="type" id="type" class="w-full p-2 border border-gray-300 rounded">
                    <option value="New Application" {{ $application->application_type == 'New Application' ? 'selected' : '' }}>New Application</option>
                    <option value="Renewal Application" {{ $application->application_type == 'Renewal Application' ? 'selected' : '' }}>Renewal Application</option>
                    <option value="Cancellation and Downgrading" {{ $application->application_type == 'Cancellation and Downgrading' ? 'selected' : '' }}>Cancellation and Downgrading</option>
                </select>
            </div>

            <!-- Factory (Editable) -->
            <div>
                <label for="factory" class="block font-semibold">Factory</label>
                <select name="factory" id="factory" class="w-full p-2 border border-gray-300 rounded">
                    <option value="Device Factory" {{ $application->factory == 'Device Factory' ? 'selected' : '' }}>Device Factory</option>
                    <option value="Medical Factory" {{ $application->factory == 'Medical Factory' ? 'selected' : '' }}>Medical Factory</option>
                </select>
            </div>

            <!-- Position (Editable) -->
            <div>
                <label for="position" class="block font-semibold">Position</label>
                <input type="text" name="position" id="position" value="{{ old('position', $application->position) }}" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <!-- Passport Number (Non-editable) -->
            <div>
                <label for="passport_number" class="block font-semibold">Passport Number</label>
                <input type="text" name="passport_number" id="passport_number" value="{{ $application->passport_number }}" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <!-- TIN (Non-editable) -->
            <div>
                <label for="TIN" class="block font-semibold">TIN</label>
                <input type="text" name="TIN" id="TIN" value="{{ $application->TIN }}" class="w-full p-2 border border-gray-300 rounded" readonly>
            </div>

            <!-- AEP Number (Non-editable) -->
            <div>
                <label for="AEP_number" class="block font-semibold">AEP Number</label>
                <input type="text" name="AEP_number" id="AEP_number" value="{{ $application->AEP_number }}" class="w-full p-2 border border-gray-300 rounded" readonly>
            </div>

            <!-- Expiry Date (Editable) -->
            <div>
                <label for="expiry_date" class="block font-semibold">Expiry Date</label>
                <input type="date" name="expiry_date" id="expiry_date" value="{{ $application->expiry_date->format('Y-m-d') }}" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <!-- Status (Editable) -->
            <div>
                <label for="status" class="block font-semibold">Status</label>
                <select name="status" id="status" class="w-full p-2 border border-gray-300 rounded">
                    <option value="Not Started" {{ $application->status == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                    <option value="In Progress" {{ $application->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ $application->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between mt-6">
                <!-- Cancel Button on the Left -->
                <a href="{{ route('applications.index') }}" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Cancel</a>

                <!-- Update Button on the Right -->
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update Application</button>
            </div>
        </form>
    </div>
</div>
@endsection
