@extends('layouts.student')

@section('title', 'Learning Materials')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Study Materials</h1>
            <p class="text-slate-400 text-sm mt-1">Access lecture notes, PDFs, guides, and assignments shared by your teacher.</p>
        </div>
    </div>

    <!-- Materials List/Grid -->
    @if($materials->isEmpty())
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center text-slate-500 mb-4">
                <i data-lucide="folder-open" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-white">No materials shared yet</h3>
            <p class="text-slate-400 text-sm mt-1 max-w-sm">When your teacher uploads PDFs or assignment sheets, they will appear here.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($materials as $material)
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between hover:border-slate-700 transition-all group">
                    <div>
                        <!-- Header: Icon and Type Badge -->
                        <div class="flex items-start justify-between gap-4 mb-4">
                            @if($material->type === 'pdf')
                                <div class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                    Lecture Document
                                </span>
                            @else
                                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    Assignment File
                                </span>
                            @endif
                        </div>

                        <!-- Date -->
                        <div class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 mb-2">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            Class Date: {{ \Carbon\Carbon::parse($material->week_date)->format('F d, Y') }}
                        </div>

                        <!-- Title / Original File Name -->
                        <h3 class="text-base font-bold text-white leading-tight group-hover:text-violet-400 transition-colors break-words">
                            {{ $material->file_name }}
                        </h3>

                        <!-- Instructor's Note -->
                        @if($material->note)
                            <div class="mt-3 p-3 rounded-xl bg-slate-950/60 border border-slate-800/60 text-slate-400 text-xs leading-relaxed">
                                <span class="font-semibold text-slate-300 block mb-1">Teacher's Note:</span>
                                {{ $material->note }}
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center justify-between">
                        <span class="text-[10px] text-slate-500 font-medium">Uploaded {{ $material->created_at->diffForHumans() }}</span>
                        <a href="{{ route('student.materials.download', $material->id) }}" 
                           class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-violet-600 hover:bg-violet-500 text-white transition-colors shadow-md">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                            Download File
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
