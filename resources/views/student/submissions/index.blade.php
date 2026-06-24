@extends('layouts.student')

@section('title', 'Homework Submissions')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in">
    
    <!-- Left Column: Upload Form -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-1 flex items-center gap-2">
                <i data-lucide="upload-cloud" class="text-violet-400 w-5 h-5"></i>
                Submit Homework
            </h2>
            <p class="text-slate-400 text-xs mb-6">Upload your completed assignment sheets, photos, or documents for review.</p>

            <form action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <!-- File Upload Field -->
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Select File</label>
                    <div class="relative border-2 border-dashed border-slate-800 hover:border-violet-500/50 rounded-2xl p-6 text-center cursor-pointer transition-all bg-slate-950/40 group">
                        <input type="file" name="file" id="file-input" required 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               onchange="updateFileName(this)">
                        
                        <div class="space-y-2" id="upload-placeholder">
                            <div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400 mx-auto group-hover:scale-110 transition-transform">
                                <i data-lucide="plus" class="w-5 h-5"></i>
                            </div>
                            <div class="text-xs text-slate-300 font-medium">Click to select files</div>
                            <div class="text-[10px] text-slate-500">PDF, DOC, DOCX, JPG, PNG (Max: 20MB)</div>
                        </div>

                        <!-- Selected File State -->
                        <div class="hidden space-y-2" id="selected-file-state">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mx-auto">
                                <i data-lucide="check" class="w-5 h-5"></i>
                            </div>
                            <div class="text-xs text-emerald-400 font-bold break-all" id="selected-file-name">file_name.pdf</div>
                            <div class="text-[10px] text-slate-400 hover:text-slate-200 underline">Change file</div>
                        </div>
                    </div>
                    @error('file')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Note/Comment field -->
                <div class="space-y-2">
                    <label for="note" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Add a Note (Optional)</label>
                    <textarea name="note" id="note" rows="3" 
                              placeholder="e.g. 'Completed questions 1 to 5. Let me know if any corrections are needed.'" 
                              class="w-full rounded-xl bg-slate-950 border border-slate-800 text-slate-200 placeholder-slate-600 focus:border-violet-600 focus:ring-1 focus:ring-violet-600 text-xs px-4 py-3 resize-none transition-all"></textarea>
                    @error('note')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-violet-600 hover:bg-violet-500 text-white font-semibold text-sm transition-all shadow-lg">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Send Submission
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: Submission History -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <i data-lucide="history" class="text-indigo-400 w-5 h-5"></i>
                Submission Desk
            </h2>

            @if($submissions->isEmpty())
                <div class="text-center py-12 flex flex-col items-center justify-center">
                    <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center text-slate-600 mb-3">
                        <i data-lucide="inbox" class="w-6 h-6"></i>
                    </div>
                    <p class="text-slate-400 text-sm">You haven't submitted any homework files yet.</p>
                    <p class="text-slate-500 text-xs mt-1">Upload files using the submission form on the left.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($submissions as $sub)
                        <div class="p-4 rounded-xl bg-slate-950/50 border border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400 shrink-0 mt-0.5">
                                    <i data-lucide="file" class="w-5 h-5"></i>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-semibold text-slate-200 break-all leading-tight">
                                        {{ $sub->file_name }}
                                    </h4>
                                    
                                    <div class="flex items-center gap-3 text-[10px] text-slate-500 font-medium">
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-3 h-3"></i>
                                            {{ $sub->created_at->format('M d, Y') }}
                                        </span>
                                        <span>•</span>
                                        <span>{{ $sub->created_at->format('g:i A') }}</span>
                                    </div>

                                    @if($sub->note)
                                        <p class="text-slate-400 text-xs mt-1.5 p-2 rounded bg-slate-900 border border-slate-850 italic">
                                            "{{ $sub->note }}"
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Delete Action -->
                            <div class="sm:shrink-0 self-end sm:self-center">
                                <form action="{{ route('student.submissions.destroy', $sub->id) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this submission?')" 
                                      class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-400 hover:text-red-400 hover:bg-red-400/10 transition-colors border border-transparent hover:border-red-500/20">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const placeholder = document.getElementById('upload-placeholder');
        const fileState = document.getElementById('selected-file-state');
        const fileNameSpan = document.getElementById('selected-file-name');
        
        if (input.files && input.files.length > 0) {
            placeholder.classList.add('hidden');
            fileState.classList.remove('hidden');
            fileNameSpan.textContent = input.files[0].name;
        } else {
            placeholder.classList.remove('hidden');
            fileState.classList.add('hidden');
        }
    }
</script>
@endsection
