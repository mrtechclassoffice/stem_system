@extends('layouts.student')

@section('title', 'Weekly Media Gallery')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Weekly Media Gallery</h1>
            <p class="text-slate-400 text-sm mt-1">Photos and video recordings from your weekly practical class sessions.</p>
        </div>
    </div>

    <!-- Media Links Grid -->
    @if($mediaLinks->isEmpty())
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center text-slate-500 mb-4">
                <i data-lucide="image" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-white">No media links shared yet</h3>
            <p class="text-slate-400 text-sm mt-1 max-w-sm">Photos and video links shared by your instructor will show up here.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($mediaLinks as $media)
                <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden hover:border-slate-700 transition-all duration-200 group flex flex-col justify-between">
                    <div>
                        <!-- Cover Placeholder Visual representation -->
                        @if($media->type === 'video')
                            <div class="aspect-video w-full bg-gradient-to-br from-indigo-950 via-slate-900 to-violet-950 flex flex-col items-center justify-center relative border-b border-slate-800/80 group-hover:from-indigo-900 group-hover:to-violet-900 transition-all duration-200">
                                <div class="absolute inset-0 bg-black/10"></div>
                                <div class="w-14 h-14 rounded-full bg-violet-600/20 border border-violet-500/30 flex items-center justify-center text-violet-400 group-hover:scale-110 transition-transform shadow-lg z-10">
                                    <i data-lucide="play" class="w-6 h-6 fill-violet-400/20 ml-0.5"></i>
                                </div>
                                <span class="absolute bottom-3 right-3 px-2 py-0.5 rounded text-[10px] font-semibold bg-violet-500 text-white flex items-center gap-1 shadow-md">
                                    <i data-lucide="video" class="w-3 h-3"></i>
                                    VIDEO
                                </span>
                            </div>
                        @else
                            <div class="aspect-video w-full bg-gradient-to-br from-pink-950 via-slate-900 to-indigo-950 flex flex-col items-center justify-center relative border-b border-slate-800/80 group-hover:from-pink-900 group-hover:to-indigo-900 transition-all duration-200">
                                <div class="absolute inset-0 bg-black/10"></div>
                                <div class="w-14 h-14 rounded-full bg-pink-600/20 border border-pink-500/30 flex items-center justify-center text-pink-400 group-hover:scale-110 transition-transform shadow-lg z-10">
                                    <i data-lucide="image" class="w-6 h-6"></i>
                                </div>
                                <span class="absolute bottom-3 right-3 px-2 py-0.5 rounded text-[10px] font-semibold bg-pink-500 text-white flex items-center gap-1 shadow-md">
                                    <i data-lucide="camera" class="w-3 h-3"></i>
                                    PHOTO
                                </span>
                            </div>
                        @endif

                        <div class="p-5 space-y-3">
                            <!-- Date & Title -->
                            <div class="text-xs font-semibold text-slate-500 flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                Class of {{ \Carbon\Carbon::parse($media->week_date)->format('M d, Y') }}
                            </div>

                            <h3 class="text-base font-bold text-slate-100 group-hover:text-white transition-colors line-clamp-1">
                                {{ $media->type === 'video' ? 'Class Recording' : 'Practical Photo Album' }}
                            </h3>

                            <!-- Optional note -->
                            @if($media->note)
                                <p class="text-slate-400 text-xs leading-relaxed line-clamp-3">
                                    {{ $media->note }}
                                </p>
                            @else
                                <p class="text-slate-500 text-xs italic">
                                    No description provided for this session.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="p-5 pt-0">
                        <a href="{{ $media->drive_url }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white transition-all border border-slate-700/60 shadow-md">
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                            View on Google Drive
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
