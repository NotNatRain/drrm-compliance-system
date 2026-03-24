@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="school" class="w-24 h-24 text-blue-600"></i>
        </div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <i data-lucide="school" class="w-6 h-6"></i>
            </div>
            <p class="text-sm font-medium text-slate-500">Total Schools</p>
        </div>
        <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_schools'] }}</h3>
        <p class="text-sm text-green-500 mt-2 flex items-center gap-1">
            <i data-lucide="trending-up" class="w-3 h-3"></i>
            <span>+2 this month</span>
        </p>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="clipboard-check" class="w-24 h-24 text-purple-600"></i>
        </div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-colors">
                <i data-lucide="clipboard-check" class="w-6 h-6"></i>
            </div>
            <p class="text-sm font-medium text-slate-500">Assessments</p>
        </div>
        <h3 class="text-3xl font-bold text-slate-800">{{ $stats['pending_assessments'] }} <span class="text-lg text-slate-400 font-normal">pending</span></h3>
        <p class="text-sm text-slate-400 mt-2">Next scheduled: Feb 12</p>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="users" class="w-24 h-24 text-emerald-600"></i>
        </div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <p class="text-sm font-medium text-slate-500">Students Tracked</p>
        </div>
        <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_students'] }}</h3>
        <p class="text-sm text-emerald-500 mt-2 flex items-center gap-1">
            <i data-lucide="check-circle" class="w-3 h-3"></i>
            <span>98% Safety Index</span>
        </p>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="activity" class="w-24 h-24 text-orange-600"></i>
        </div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-orange-50 text-orange-600 rounded-xl group-hover:bg-orange-600 group-hover:text-white transition-colors">
                <i data-lucide="activity" class="w-6 h-6"></i>
            </div>
            <p class="text-sm font-medium text-slate-500">Avg Safety Score</p>
        </div>
        <h3 class="text-3xl font-bold text-slate-800">{{ $stats['avg_safety_score'] }}<span class="text-sm text-slate-400">/10</span></h3>
        <div class="w-full bg-slate-200 rounded-full h-1.5 mt-3">
            <div class="bg-orange-500 h-1.5 rounded-full" style="width: 85%"></div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Assessments -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-lg text-slate-800">Recent Assessments</h3>
            <button class="text-sm text-blue-600 font-medium hover:text-blue-700">View All</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                        <th class="pb-3 pl-2">School</th>
                        <th class="pb-3">Date</th>
                        <th class="pb-3">Assessor</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 pr-2 text-right">Score</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($recentAssessments as $assessment)
                    <tr class="hover:bg-slate-50 transition-colors border-b border-slate-50 group">
                        <td class="py-4 pl-2 font-medium text-slate-800 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs uppercase">{{ \Str::limit($assessment->school->name, 2, '') }}</div>
                            {{ $assessment->school->name }}
                        </td>
                        <td class="py-4 text-slate-500">{{ \Carbon\Carbon::parse($assessment->date_visited)->format('M d, Y') }}</td>
                        <td class="py-4 text-slate-500">{{ $assessment->assessed_by }}</td>
                        <td class="py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $assessment->status == 'completed' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-yellow-100 text-yellow-700 border-yellow-200' }} border capitalize">{{ $assessment->status }}</span>
                        </td>
                        <td class="py-4 pr-2 text-right font-bold text-slate-800">{{ $assessment->total_score }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">No assessments recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions & Alerts -->
    <div class="space-y-6">
        <!-- Action Card -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            <h3 class="font-bold text-lg mb-2">New Assessment</h3>
            <p class="text-blue-100 text-sm mb-6">Start a new safety checklist for a school.</p>
            <button class="bg-white text-blue-700 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-blue-50 transition-colors w-full flex items-center justify-center gap-2 shadow-sm">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                Start Assessment
            </button>
        </div>

        <!-- Facilities Alert -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
                Critical Facilities
            </h3>
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-3 bg-red-50 rounded-xl border border-red-100">
                    <div class="min-w-2 h-2 mt-2 rounded-full bg-red-500"></div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Broken Window - Room 101</p>
                        <p class="text-xs text-slate-500">Reported 2 days ago • High Priority</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 bg-orange-50 rounded-xl border border-orange-100">
                    <div class="min-w-2 h-2 mt-2 rounded-full bg-orange-500"></div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Slippery Pathway - Bldg A</p>
                        <p class="text-xs text-slate-500">Reported 5 days ago • Medium Priority</p>
                    </div>
                </div>
            </div>
            <button class="w-full mt-4 text-sm text-slate-500 hover:text-slate-800 font-medium py-2 rounded-lg hover:bg-slate-50 transition-colors">
                View All Issues
            </button>
        </div>
    </div>
</div>
@endsection
