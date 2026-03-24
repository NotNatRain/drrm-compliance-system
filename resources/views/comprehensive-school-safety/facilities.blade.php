<!-- From the create.blade.php -->
@extends('layouts.app')

@section('title', 'Add New Facility')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ isset($school) ? route('schools.facilities.index', $school) : route('facilities.index') }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="font-bold text-2xl text-slate-800">Add New Facility</h1>
            <p class="text-slate-500 text-sm">Record a new physical asset or location.</p>
        </div>
    </div>

    <form action="{{ isset($school) ? route('schools.facilities.store', $school) : route('facilities.store') }}" method="POST" class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
        @csrf

        @if(isset($school))
            <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-center gap-3 text-blue-800 mb-6">
                <i data-lucide="school" class="w-5 h-5"></i>
                <span class="font-medium">Adding for: {{ $school->name }}</span>
            </div>
        @elseif(Auth::user()->school_id)
            <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-center gap-3 text-blue-800 mb-6">
                <i data-lucide="school" class="w-5 h-5"></i>
                <span class="font-medium">Adding for: {{ Auth::user()->school->name }}</span>
            </div>
        @endif

        @if(!isset($school) && !Auth::user()->school_id)
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Select School</label>
            <select name="school_id" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all" required>
                <option value="">Choose a school...</option>
                @foreach($schools as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Facility Name</label>
                <input type="text" name="name" placeholder="e.g., Room 101, Main Gate Pathway" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Type</label>
                <select name="type" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all" required>
                    <option value="room">Classroom / Room</option>
                    <option value="door">Door / Exit</option>
                    <option value="pathway">Pathway / Corridor</option>
                    <option value="other">Other Infrastructure</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Condition</label>
                <select name="condition" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all" required>
                    <option value="good">Good Condition</option>
                    <option value="fair">Fair / Minor Wear</option>
                    <option value="needs_repair">Needs Repair</option>
                    <option value="condemned">Condemned / Unsafe</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Brief description of the facility..." class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Remarks / Observations</label>
                <textarea name="remarks" rows="2" placeholder="Any additional notes or safety concerns..." class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all"></textarea>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-medium shadow-lg shadow-blue-500/20 hover:scale-105 transition-all">
                Save Facility
            </button>
        </div>
    </form>
</div>
@endsection

<!-- From index.blade.php -->
@extends('layouts.app')

@section('title', 'Facilities Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="font-bold text-2xl text-slate-800">Facilities</h3>
        <p class="text-slate-500 text-sm">Track physical facility conditions (doors, pathways, rooms).</p>
    </div>
    <a href="{{ isset($school) ? route('schools.facilities.create', $school) : route('facilities.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2">
        <i data-lucide="plus" class="w-5 h-5"></i>
        Add Facility
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @if($facilities->isEmpty())
        <div class="col-span-full p-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-100">
            <i data-lucide="building-2" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
            <p class="text-lg font-medium">No facilities recorded.</p>
        </div>
    @else
        @foreach($facilities as $facility)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    @if($facility->type == 'room') <i data-lucide="box" class="w-6 h-6"></i>
                    @elseif($facility->type == 'door') <i data-lucide="door-open" class="w-6 h-6"></i>
                    @elseif($facility->type == 'pathway') <i data-lucide="footprints" class="w-6 h-6"></i>
                    @else <i data-lucide="building" class="w-6 h-6"></i>
                    @endif
                </div>
                <span class="px-2 py-1 text-xs font-bold uppercase rounded bg-slate-100 text-slate-500">{{ $facility->condition }}</span>
            </div>
            <h4 class="font-bold text-lg text-slate-800 mb-1">{{ $facility->name }}</h4>
            <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $facility->description }}</p>
            <div class="pt-4 border-t border-slate-50 flex justify-between items-center text-xs text-slate-400">
                <span>{{ $facility->school->name ?? 'Unknown School' }}</span>
                <span>{{ \Carbon\Carbon::parse($facility->updated_at)->diffForHumans() }}</span>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
