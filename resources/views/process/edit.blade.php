@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 max-w-2xl">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Edit Process</h2>

    <!-- Form Container with Border, Padding, and Shadow -->
    <form action="{{ route('process.update', $process->id) }}" method="POST" class="bg-white border border-gray-200 p-8 rounded-lg shadow-lg">
        @csrf
        @method('PUT')

        <!-- Form Fields (include the form fields here) -->
        @include('process._form', ['process' => $process, 'type' => $process->application_type])

        <!-- Buttons Section -->
        <div class="mt-6 flex justify-end space-x-4">
            <!-- Update Button -->
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-md shadow-md transition duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-300">
                Update
            </button>

            <!-- Cancel Button -->
            <a href="{{ route('process.index', ['type' => $process->application_type]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-3 rounded-md shadow-md transition duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-200">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
