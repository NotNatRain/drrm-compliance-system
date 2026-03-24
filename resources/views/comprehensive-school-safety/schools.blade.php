@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">{{ $school->name }}</h1>
            <p class="text-slate-500 text-sm flex items-center gap-2">
                <i data-lucide="map-pin" class="w-3 h-3"></i>
                {{ $school->address ?? 'Olongapo City' }}
            </p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1: Students -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="users" class="w-24 h-24 text-blue-600"></i>
        </div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <p class="text-sm font-medium text-slate-500">Total Students</p>
        </div>
        <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_students'] }}</h3>
    </div>

    <!-- Stat Card 2: Facilities -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="building" class="w-24 h-24 text-purple-600"></i>
        </div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-colors">
                <i data-lucide="building" class="w-6 h-6"></i>
            </div>
            <p class="text-sm font-medium text-slate-500">Facilities</p>
        </div>
        <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_facilities'] }}</h3>
    </div>

    <!-- Stat Card 3: Safety Score -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="shield-check" class="w-24 h-24 text-emerald-600"></i>
        </div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <p class="text-sm font-medium text-slate-500">Safety Index</p>
        </div>
        <h3 class="text-3xl font-bold text-slate-800">{{ number_format($stats['avg_safety_score'], 1) }}<span class="text-sm text-slate-400">/10</span></h3>
    </div>

    <!-- Stat Card 4: Pending -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="clock" class="w-24 h-24 text-orange-600"></i>
        </div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-orange-50 text-orange-600 rounded-xl group-hover:bg-orange-600 group-hover:text-white transition-colors">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <p class="text-sm font-medium text-slate-500">Pending Actions</p>
        </div>
        <h3 class="text-3xl font-bold text-slate-800">{{ $stats['pending_assessments'] }}</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Assessments for this School -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-lg text-slate-800">Recent Assessments</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                        <th class="pb-3 pl-2">Date</th>
                        <th class="pb-3">Assessor</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 pr-2 text-right">Score</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($recentAssessments as $assessment)
                    <tr class="hover:bg-slate-50 transition-colors border-b border-slate-50 group">
                        <td class="py-4 pl-2 text-slate-500">{{ \Carbon\Carbon::parse($assessment->date_visited)->format('M d, Y') }}</td>
                        <td class="py-4 text-slate-500">{{ $assessment->assessed_by }}</td>
                        <td class="py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $assessment->status == 'completed' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-yellow-100 text-yellow-700 border-yellow-200' }} border capitalize">{{ $assessment->status }}</span>
                        </td>
                        <td class="py-4 pr-2 text-right font-bold text-slate-800">{{ $assessment->total_score }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400">No assessments recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- School Actions -->
    <div class="space-y-6">
        <div class="bg-slate-900 rounded-2xl shadow-lg p-6 text-white">
            <h3 class="font-bold text-lg mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('assessments.create') }}" class="flex items-center gap-3 p-3 bg-white/5 hover:bg-white/10 rounded-xl transition-colors border border-white/10">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <i data-lucide="clipboard-check" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-medium text-sm">New Assessment</span>
                </a>
                <a href="{{ route('students.index') }}" class="flex items-center gap-3 p-3 bg-white/5 hover:bg-white/10 rounded-xl transition-colors border border-white/10">
                    <div class="p-2 bg-emerald-500 rounded-lg">
                        <i data-lucide="users" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-medium text-sm">Manage Students</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Note: If user is Contributor, they should be redirected here automatically or this should be their index.
    // However, the prompt implies this is a "drill-down" view for Admin/DO.
</script>
@endsection
