@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Welcome back, Teacher</h1>
        <p class="text-slate-400 text-sm mt-0.5">Here is what is happening at STEM Academy today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Students Count -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Students</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $studentsCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-violet-600/15 flex items-center justify-center text-violet-400">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Paid Payments -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Paid ({{ $currentMonth }})</p>
                <p class="text-2xl font-bold text-emerald-400 mt-1">{{ $paidCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-600/15 flex items-center justify-center text-emerald-400">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending ({{ $currentMonth }})</p>
                <p class="text-2xl font-bold text-amber-400 mt-1">{{ $pendingCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-600/15 flex items-center justify-center text-amber-400">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Unpaid Payments -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unpaid ({{ $currentMonth }})</p>
                <p class="text-2xl font-bold text-rose-450 mt-1">{{ $unpaidCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-600/15 flex items-center justify-center text-rose-400">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <!-- Recent Uploads Section -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-4">
        <h2 class="text-white font-bold text-base">Recent Materials Uploads</h2>
        
        @if($recentMaterials->isEmpty())
            <div class="text-center py-8 text-slate-500 text-sm">
                <i data-lucide="upload" class="w-8 h-8 mx-auto mb-2 text-slate-600"></i>
                <p>No materials uploaded recently.</p>
            </div>
        @else
            <div class="divide-y divide-slate-800">
                @foreach($recentMaterials as $material)
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400">
                                @if($material->type === 'pdf')
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                @else
                                    <i data-lucide="file" class="w-4 h-4"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white truncate max-w-xs sm:max-w-md">{{ $material->file_name }}</p>
                                <p class="text-xs text-slate-500">Uploaded for <span class="text-slate-400 font-medium">{{ $material->student->full_name }}</span> on {{ $material->week_date->format('M j, Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.students.show', $material->student_id) }}" class="text-xs font-semibold text-violet-400 hover:text-violet-300">View Student</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
