@extends('layouts.landing')

@section('title', 'Division Overview')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 rounded-3xl p-8 mb-8 text-white relative overflow-hidden shadow-2xl">
        <div class="absolute right-0 top-0 h-full w-1/2 bg-white/5 skew-x-12 translate-x-20"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Schools Division Office - Olongapo City</h1>
            <p class="text-blue-200">School Safety Management & Monitoring System</p>

            <div class="flex gap-6 mt-8">
                <div>
                    <p class="text-3xl font-bold">{{ $totalSchools }}</p>
                    <p class="text-xs text-blue-300 uppercase tracking-wider">Total Schools</p>
                </div>
                <div class="w-px bg-white/20"></div>
                <div>
                    <p class="text-3xl font-bold">{{ number_format($totalStudents) }}</p>
                    <p class="text-xs text-blue-300 uppercase tracking-wider">Total Students</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-8 flex flex-col md:flex-row gap-4 justify-between items-center">
        <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
            <i data-lucide="building-2" class="w-6 h-6 text-blue-600"></i>
            Schools Directory
        </h2>

        <form action="{{ route('dashboard') }}" method="GET" class="w-full md:w-96 relative group">
            <i data-lucide="search" class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Search school name..."
                class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none shadow-sm"
            >
            @if($search)
                <a href="{{ route('dashboard') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($schools as $school)
        <a href="{{ route('schools.dashboard', $school->id) }}" class="group bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i data-lucide="school" class="w-6 h-6"></i>
                </div>
                <span class="bg-slate-100 text-slate-600 text-xs px-2 py-1 rounded-full font-medium">ID: {{ $school->id }}</span>
            </div>

            <h3 class="text-lg font-bold text-slate-800 mb-1 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $school->name }}</h3>
            <p class="text-sm text-slate-400 mb-4 line-clamp-1">{{ $school->address ?? 'Olongapo City' }}</p>

            <div class="grid grid-cols-2 gap-4 border-t border-slate-50 pt-4">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Students</p>
                    <p class="font-bold text-slate-700">{{ $school->students_count }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Assessments</p>
                    <p class="font-bold text-slate-700">{{ $school->assessments_count }}</p>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-dashed border-slate-300">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                <i data-lucide="school" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-medium text-slate-800">No Schools Found</h3>
            <p class="text-slate-500">There are no schools active in the system yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
