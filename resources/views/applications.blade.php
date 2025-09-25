// resources/views/applications.blade.php

@extends('layouts.app')

@section('content')
<div class="application-management">
    <h1>Application Management</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Application Type</th>
                <th>Factory</th>
                <th>Position</th>
                <th>Passport No</th>
                <th>TIN</th>
                <th>AEP No</th>
                <th>AEP Expiry Date</th>
                <th>Follow-Up Date</th>
                <th>Days Before Expiry</th>
                <th>Status</th> <!-- Updated from 'Progress' to 'Status' -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $application)
                <tr>
                    <td>{{ $application->name }}</td>
                    <td>{{ $application->type }}</td>
                    <td>{{ $application->factory }}</td>
                    <td>{{ $application->position }}</td>
                    <td>{{ $application->passport_number }}</td>
                    <td>{{ $application->TIN }}</td>
                    <td>{{ $application->AEP_number }}</td>
                    <td>{{ $application->expiry_date->format('M-d-Y') }}</td>
                    <td>{{ $application->follow_up_date->format('M-d-Y') }}</td>
                    <td>{{ $application->days_before_expiry }}</td>
                    <td>{{ $application->status }}</td> <!-- Updated to use 'status' -->
                    <td>
                        <!-- Actions -->
                        <a href="{{ route('applications.edit', $application) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('applications.show', $application) }}" class="btn btn-info">View</a>
                        <form action="{{ route('applications.destroy', $application) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $applications->links() }}
    </div>
</div>
@endsection
