@extends('layouts.app')

@section('title', 'Comprehensive Schools')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Comprehensive School Safety Schools</h2>
            <p class="text-muted mb-0">All schools currently registered in the Comprehensive School Safety module.</p>
        </div>
        <div class="d-flex gap-2">
                <a href="{{ route('comprehensive-school-safety.dashboard') }}#schoolsDirectory" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create New School
            </a>
            <a href="{{ route('comprehensive-school-safety.dashboard') }}#schoolsDirectory" class="btn btn-success">
                <i class="fas fa-download me-1"></i> Register Existing School
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($schools->isEmpty())
                <p class="text-muted p-3 mb-0">No school records yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>School ID Number</th>
                            <th>Address</th>
                            <th>School Head</th>
                            <th>Created</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($schools as $school)
                            <tr>
                                <td>{{ $school->name }}</td>
                                <td>{{ $school->school_id_number ?: 'N/A' }}</td>
                                <td>{{ $school->address ?: 'N/A' }}</td>
                                <td>{{ $school->school_head ?: 'N/A' }}</td>
                                <td>{{ $school->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @if(!$schools->isEmpty())
            <div class="card-footer bg-white">
                {{ $schools->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
