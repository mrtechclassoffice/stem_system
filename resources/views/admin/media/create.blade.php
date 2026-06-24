@extends('layouts.admin')

@section('title', 'Share Media Links')

@section('content')
<div class="max-w-xl">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ $studentId ? route('admin.students.show', $studentId) : route('admin.students.index') }}" class="text-slate-500 hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-white">Share Media Link</h1>
            <p class="text-slate-400 text-sm">Paste a Google Drive URL for student weekly photos or videos.</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.media.store') }}" method="POST" class="space-y-5" onsubmit="return validateDriveUrl();">
        @csrf

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-3 text-red-400 text-sm">
                ⚠ {{ $errors->first() }}
            </div>
        @endif

        <div id="url-error" class="hidden bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-3 text-red-400 text-sm">
            ⚠ URL must be a valid Google Drive URL containing drive.google.com
        </div>

        <!-- Drive Sharing Reminder -->
        <div class="flex items-start gap-3 bg-violet-600/10 border border-violet-600/20 rounded-2xl p-4 text-violet-300 text-xs">
            <i data-lucide="info" class="w-5 h-5 shrink-0 text-violet-400 mt-0.5"></i>
            <div>
                <p class="font-semibold text-white mb-0.5">Google Drive Access Reminder</p>
                <p>Please make sure the Google Drive link is set to **"Anyone with the link can view"** under your file sharing settings. Otherwise, the student will see an access request error when opening the link.</p>
            </div>
        </div>

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

            <!-- Media Type -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-slate-300">Media Type *</label>
                <select name="type" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50">
                    <option value="photo">Photo Gallery</option>
                    <option value="video">Video Demonstration</option>
                </select>
            </div>

            <!-- Google Drive URL -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-slate-300">Google Drive link *</label>
                <input type="url" name="drive_url" id="drive_url" value="{{ old('drive_url') }}" placeholder="https://drive.google.com/file/d/..." required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
            </div>

            <!-- Week Date -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-slate-300">Class Date / Week *</label>
                <input type="date" name="week_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
            </div>

            <!-- Notes -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-slate-300">Caption / Note <span class="text-slate-500">(optional)</span></label>
                <input type="text" name="note" value="{{ old('note') }}" placeholder="E.g. Group activity photo, robot building demo..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none" />
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ $studentId ? route('admin.students.show', $studentId) : route('admin.students.index') }}" class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-sm font-medium bg-slate-800 text-slate-300 hover:bg-slate-700 transition-colors text-center">
                Cancel
            </a>
            <button type="submit" class="flex-1 sm:flex-none bg-violet-600 hover:bg-violet-500 text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition-colors">
                Share Link
            </button>
        </div>
    </form>
</div>

<script>
    function validateDriveUrl() {
        const urlInput = document.getElementById('drive_url').value;
        const errDiv = document.getElementById('url-error');
        if (!urlInput.includes('drive.google.com')) {
            errDiv.classList.remove('hidden');
            return false;
        }
        errDiv.classList.add('hidden');
        return true;
    }
</script>
@endsection
