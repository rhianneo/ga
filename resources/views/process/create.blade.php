@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 max-w-2xl">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Add Process</h2>

    <!-- Form Container with Shadow, Border, and Padding -->
    <form action="{{ route('process.store') }}" method="POST" class="bg-white border border-gray-200 p-8 rounded-lg shadow-lg">
        @csrf

        <!-- Process Form Fields (include the existing form here) -->
        @include('process._form', ['process' => null, 'type' => request('type')])

        <!-- Buttons Section -->
        <div class="mt-6 flex justify-end space-x-4">
            <!-- Save Button with Smooth Hover Effect -->
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-md shadow-md transition duration-200 transform hover:scale-105">
                Save
            </button>
            
            <!-- Cancel Button with Hover and Subtle Style -->
            <a href="{{ route('process.index', ['type' => request('type')]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-3 rounded-md transition duration-200 transform hover:scale-105">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
