@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 max-w-2xl">
    <h2 class="text-2xl font-bold mb-6">Add Process</h2>

    <form action="{{ route('process.store') }}" method="POST">
        @csrf
        @include('process._form', ['process' => null, 'type' => request('type')])

        <div class="mt-4 flex space-x-2">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">Save</button>
            <a href="{{ route('process.index', ['type' => request('type')]) }}" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-md">Cancel</a>
        </div>
    </form>
</div>
@endsection
