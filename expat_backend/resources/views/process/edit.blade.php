@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 max-w-2xl">
    <h2 class="text-2xl font-bold mb-6">Edit Process</h2>

    <form action="{{ route('process.update', $process->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('process._form', ['process' => $process, 'type' => $process->application_type])

        <div class="mt-4 flex space-x-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Update</button>
            <a href="{{ route('process.index', ['type' => $process->application_type]) }}" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-md">Cancel</a>
        </div>
    </form>
</div>
@endsection
