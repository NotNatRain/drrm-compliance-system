<!--index.blade.php-->
@extends('layouts.app')

@section('title', 'Student Directory')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="font-bold text-2xl text-slate-800">Students</h3>
        <p class="text-slate-500 text-sm">Monitor student safety pathways and information.</p>
    </div>
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2">
        <i data-lucide="user-plus" class="w-5 h-5"></i>
        Add Student
    </button>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    @if($students->isEmpty())
        <div class="p-12 text-center text-slate-400">
            <i data-lucide="users" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
            <p class="text-lg font-medium">No students found.</p>
        </div>
    @else
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">LRN</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">School</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Grade/Section</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($students as $student)
                <tr class="hover:bg-slate-50 transition-colors cursor-pointer" onclick="window.location='{{ route('students.show', $student->id) }}'">
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $student->name }}</td>
                    <td class="px-6 py-4 text-slate-600 font-mono text-xs bg-slate-100 rounded inline-block px-2 py-1 mx-6 my-3">{{ $student->student_lrn ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $student->school->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $student->grade_level }} - {{ $student->section }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('students.show', $student->id) }}" class="text-blue-600 hover:underline text-sm font-medium">View Pathway</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

<!--show.blade.php-->
@extends('layouts.app')

@section('title', 'Student Profile')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('students.index') }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h1 class="font-bold text-2xl text-slate-800">{{ $student->name }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-fit">
            <div class="text-center mb-6">
                <div class="w-24 h-24 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-400">
                    <i data-lucide="user" class="w-12 h-12"></i>
                </div>
                <h2 class="font-bold text-xl text-slate-800">{{ $student->name }}</h2>
                <p class="text-slate-500">{{ $student->grade_level }} - {{ $student->section }}</p>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div>
                    <span class="block text-xs text-slate-400 uppercase font-semibold">LRN</span>
                    <span class="block text-slate-700 font-mono">{{ $student->student_lrn ?? '--' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-400 uppercase font-semibold">School</span>
                    <span class="block text-slate-700">{{ $student->school->name ?? '--' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-400 uppercase font-semibold">Guardian</span>
                    <span class="block text-slate-700">{{ $student->guardian_name ?? '--' }}</span>
                    <span class="block text-xs text-slate-500">{{ $student->guardian_contact }}</span>
                </div>
            </div>
        </div>

        <!-- Pathway Tracking -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Add Score -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Record Pathway Score (0-10)</h3>
                <form action="{{ route('students.storePathway', $student->id) }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Score (0-10)</label>
                        <input type="number" name="pathway_score" min="0" max="10" class="w-full rounded-lg border-slate-200" required>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                        <input type="date" name="observation_date" class="w-full rounded-lg border-slate-200" required value="{{ date('Y-m-d') }}">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold transition-colors">Record</button>
                </form>
            </div>

            <!-- History Chart/List -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-lg text-slate-800 mb-6">Pathway History</h3>

                @if($student->pathways->isEmpty())
                    <p class="text-slate-500 text-center py-8">No pathway records yet.</p>
                @else
                    <div class="space-y-4">
                        @foreach($student->pathways as $pathway)
                        <div class="flex items-center gap-4">
                            <div class="w-16 text-right text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($pathway->observation_date)->format('M d') }}</div>
                            <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $pathway->pathway_score >= 8 ? 'bg-green-500' : ($pathway->pathway_score >= 5 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $pathway->pathway_score * 10 }}%"></div>
                            </div>
                            <div class="w-8 font-bold text-slate-800 text-right">{{ $pathway->pathway_score }}</div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
