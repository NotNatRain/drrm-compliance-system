@extends('layouts.app')

@section('title', 'PIE-PRA Volunteers')
@section('hide_main_nav', '1')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('pie-pra.dashboard') }}" class="btn btn-outline-secondary border-0 p-2">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>
        <h1 class="h5 mb-0" style="color:#1B4C6D;">
            <i class="fas fa-hands-helping"></i> Community Volunteer Matching Network
        </h1>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header" style="background-color:#1B4C6D;color:white;">
                    <h5 class="mb-0"><i class="fas fa-user-plus"></i> Register Volunteer</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pie-pra.volunteers.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contact</label>
                            <input type="text" name="contact" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Barangay (optional)</label>
                            <input type="text" name="barangay" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Skills</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($skills as $skill)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="skills[]" value="{{ $skill->id }}" id="skill{{ $skill->id }}">
                                        <label class="form-check-label" for="skill{{ $skill->id }}">
                                            {{ $skill->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn" style="background-color:#1B4C6D;color:white;">
                            <i class="fas fa-save me-1"></i> Save Volunteer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header" style="background-color:#1B4C6D;color:white;">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Volunteer Registry</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Barangay</th>
                                    <th>Skills</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($volunteers as $v)
                                    <tr>
                                        <td>{{ $v->name }}</td>
                                        <td>{{ $v->contact }}</td>
                                        <td>{{ $v->barangay ?? '—' }}</td>
                                        <td>
                                            @foreach($v->skills as $s)
                                                <span class="badge bg-info text-dark mb-1">{{ $s->name }}</span>
                                            @endforeach
                                            @if($v->skills->isEmpty())
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $v->status === 'on-duty' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($v->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No volunteers yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

