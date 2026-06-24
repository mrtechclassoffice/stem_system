@extends('layouts.admin')

@section('title', 'Upload Materials')

@section('content')
<div class="max-w-xl">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ $studentId ? route('admin.students.show', $studentId) : route('admin.students.index') }}" class="text-slate-500 hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-white">Upload Material</h1>
            <p class="text-slate-400 text-sm">Upload PDFs, documents, or assignments directly for a student.</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-3 text-red-400 text-sm">
                ⚠ {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-4">
            <!-- Student -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-slate-300">Select Student *</label>
                <select name="student_id" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50">
                    <option value="">Select a student...</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ $studentId == $s->id ? 'selected' : '' }}>{{ $s->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Material Type -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-slate-300">Material Type *</label>
                <select name="type" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50">
                    <option value="pdf">PDF Document</option>
                    <option value="assignment">Assignment Worksheet</option>
                </select>
            </div>

            <!-- Week Date -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-slate-300">Class Date / Week *</label>
                <input type="date" name="week_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
            </div>

            <!-- Notes -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-slate-300">Notes / Remarks <span class="text-slate-500">(optional)</span></label>
                <input type="text" name="note" value="{{ old('note') }}" placeholder="E.g. Read before practical lesson..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none" />
            </div>

            <!-- File Input -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-slate-300">Select File *</label>
                <input type="file" name="file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-violet-600 file:text-white hover:file:bg-violet-500 focus:outline-none" />
                <p class="text-[11px] text-slate-500">Allowed formats: PDF, DOC, DOCX, JPG, PNG. Max file size: 20MB.</p>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ $studentId ? route('admin.students.show', $studentId) : route('admin.students.index') }}" class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-sm font-medium bg-slate-800 text-slate-300 hover:bg-slate-700 transition-colors text-center">
                Cancel
            </a>
            <button type="submit" class="flex-1 sm:flex-none bg-violet-600 hover:bg-violet-500 text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition-colors">
                Upload File
            </button>
        </div>
    </form>
</div>
@endsection
