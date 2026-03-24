@extends('layouts.app')

@section('title', 'New Assessment')

@section('content')
<!-- From the create.blade.php -->
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('assessments.index') }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="font-bold text-2xl text-slate-800">New Safety Assessment</h1>
            <p class="text-slate-500 text-sm">Complete the checklist below for the school facility.</p>
        </div>
    </div>

    <form action="{{ route('assessments.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- General Info Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="font-bold text-lg text-slate-800 mb-4 border-b border-slate-100 pb-2">General Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">School Name</label>
                    @if(Auth::user()->school_id)
                        <input type="text" value="{{ Auth::user()->school->name }}" class="w-full rounded-lg border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed" readonly>
                    @else
                        <input type="text" name="school_name" placeholder="Enter School Name" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all" required>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Date Visited</label>
                    <input type="date" name="date_visited" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Assessed By</label>
                    <input type="text" name="assessed_by" placeholder="Full Name of Assessor" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all" required>
                </div>
            </div>
        </div>

        <!-- Checklist Structure -->
        @foreach($checklistStructure as $category => $items)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 bg-slate-50 border-b border-slate-100">
                <h3 class="font-bold text-lg text-slate-800">{{ $category }}</h3>
                <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Pillar Compliance Checklist</p>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($items as $index => $item)
                <div class="p-6 hover:bg-slate-50/50 transition-colors">
                    <div class="flex flex-col md:flex-row gap-4 justify-between">
                        <div class="flex-1">
                            <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded mr-2">{{ $index + 1 }}</span>
                            <span class="text-slate-700 font-medium">{{ $item }}</span>
                        </div>
                        <div class="flex items-start gap-6 md:w-1/3 shrink-0">
                            <!-- Yes/No Toggle -->
                            <div class="flex items-center gap-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="items[{{ $category }}][{{ $item }}][value]" value="1" class="text-blue-600 focus:ring-blue-500" required>
                                    <span class="text-sm font-medium text-slate-700">Yes</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="items[{{ $category }}][{{ $item }}][value]" value="0" class="text-red-600 focus:ring-red-500" required>
                                    <span class="text-sm font-medium text-slate-700">No</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <input type="text" name="items[{{ $category }}][{{ $item }}][remarks]" placeholder="Add remarks/observations..." class="w-full text-sm border-0 border-b border-slate-200 focus:border-blue-500 focus:ring-0 px-0 py-1 bg-transparent transition-all placeholder:text-slate-400">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="flex justify-end pt-4 pb-12">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold text-lg shadow-xl shadow-blue-500/20 hover:scale-105 transition-all">
                Submit Assessment
            </button>
        </div>
    </form>
</div>
@endsection


<!-- From index.blade.php -->
@extends('layouts.app')

@section('title', 'All Assessments')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="font-bold text-2xl text-slate-800">Assessments</h3>
        <p class="text-slate-500 text-sm">Manage and review school safety checklists.</p>
    </div>
    <a href="{{ route('assessments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2">
        <i data-lucide="plus" class="w-5 h-5"></i>
        New Assessment
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    @if($assessments->isEmpty())
        <div class="p-12 text-center text-slate-400">
            <i data-lucide="clipboard-list" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
            <p class="text-lg font-medium">No assessments found.</p>
            <p class="text-sm">Start by creating a new assessment.</p>
        </div>
    @else
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">School</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date Visited</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Assessed By</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Total Score</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($assessments as $assessment)
                <tr class="hover:bg-blue-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-800">{{ $assessment->school->name ?? 'Unknown School' }}</div>
                        <div class="text-xs text-slate-500">ID: {{ $assessment->school->school_id_number ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($assessment->date_visited)->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $assessment->assessed_by }}</td>
                    <td class="px-6 py-4 text-right">
                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-bold {{ $assessment->total_score >= 8 ? 'bg-green-100 text-green-700' : ($assessment->total_score >= 5 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $assessment->total_score }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button class="text-slate-400 hover:text-blue-600 transition-colors">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

