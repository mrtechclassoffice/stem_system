@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header Hero Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-700 p-6 md:p-8 shadow-xl">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 justify-between">
            <div class="flex items-center gap-5">
                <div class="relative">
                    @if($student->profile_picture_path)
                        <img src="{{ asset('storage/' . $student->profile_picture_path) }}" alt="{{ $student->full_name }}" class="w-16 h-16 md:w-20 md:h-20 rounded-2xl object-cover border-2 border-white/20 shadow-md">
                    @else
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-violet-500/25 border-2 border-white/10 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($student->full_name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-slate-950 flex items-center justify-center" title="Account Active">
                        <span class="w-2.5 h-2.5 rounded-full bg-white animate-pulse"></span>
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Welcome, {{ $student->full_name }}!</h1>
                    <p class="text-violet-200 text-sm mt-1">Ready for another week of hands-on STEM learning?</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('student.materials.index') }}" class="px-4 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-100 font-semibold text-sm transition-all duration-150 shadow-md flex items-center gap-2">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    Start Learning
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Stat Card: Materials -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 transition-all duration-200 hover:border-slate-700 hover:-translate-y-1 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 group-hover:bg-blue-500/20 transition-colors">
                    <i data-lucide="book-open" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">LMS Resources</span>
            </div>
            <h3 class="text-3xl font-bold text-white tracking-tight">{{ $materialsCount }}</h3>
            <p class="text-slate-400 text-sm mt-1">Shared PDFs, Guides & Assignments</p>
            <div class="mt-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('student.materials.index') }}" class="text-blue-400 hover:text-blue-300 text-xs font-semibold flex items-center gap-1">
                    Access learning vault
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Stat Card: Media Gallery -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 transition-all duration-200 hover:border-slate-700 hover:-translate-y-1 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400 group-hover:bg-violet-500/20 transition-colors">
                    <i data-lucide="film" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Weekly Media</span>
            </div>
            <h3 class="text-3xl font-bold text-white tracking-tight">{{ $mediaCount }}</h3>
            <p class="text-slate-400 text-sm mt-1">Practical photos & video recordings</p>
            <div class="mt-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('student.media.index') }}" class="text-violet-400 hover:text-violet-300 text-xs font-semibold flex items-center gap-1">
                    Explore media gallery
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Stat Card: Current Month Tuition -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 transition-all duration-200 hover:border-slate-700 hover:-translate-y-1 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 group-hover:bg-emerald-500/20 transition-colors">
                    <i data-lucide="credit-card" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tuition Due</span>
            </div>
            @if($latestPayment)
                @if($latestPayment->status === 'paid')
                    <h3 class="text-3xl font-bold text-emerald-400 tracking-tight">Paid</h3>
                    <p class="text-slate-400 text-sm mt-1">For {{ \Carbon\Carbon::parse($latestPayment->period_month . '-01')->format('F Y') }}</p>
                @elseif($latestPayment->status === 'pending')
                    <h3 class="text-3xl font-bold text-amber-400 tracking-tight">Pending</h3>
                    <p class="text-slate-400 text-sm mt-1">Verification on progress</p>
                @else
                    <h3 class="text-3xl font-bold text-rose-400 tracking-tight">Unpaid</h3>
                    <p class="text-slate-400 text-sm mt-1">For {{ \Carbon\Carbon::parse($latestPayment->period_month . '-01')->format('F Y') }}</p>
                @endif
            @else
                <h3 class="text-3xl font-bold text-slate-400 tracking-tight">No Record</h3>
                <p class="text-slate-500 text-sm mt-1">For current month ({{ \Carbon\Carbon::now()->format('F Y') }})</p>
            @endif
            <div class="mt-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('student.profile.show') }}" class="text-emerald-400 hover:text-emerald-300 text-xs font-semibold flex items-center gap-1">
                    View payment history
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Student Detail & Parent Contact -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main details -->
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <i data-lucide="user-check" class="w-5 h-5 text-indigo-400"></i>
                Academic Profile Details
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <span class="text-xs font-medium text-slate-500 uppercase">Age</span>
                    <p class="text-slate-200 font-medium">{{ $student->age }} years</p>
                </div>
                <div class="space-y-1.5">
                    <span class="text-xs font-medium text-slate-500 uppercase">Birthday</span>
                    <p class="text-slate-200 font-medium">{{ $student->birthday ? \Carbon\Carbon::parse($student->birthday)->format('F d, Y') : 'N/A' }}</p>
                </div>
                <div class="space-y-1.5">
                    <span class="text-xs font-medium text-slate-500 uppercase">School</span>
                    <p class="text-slate-200 font-medium">{{ $student->school ?: 'N/A' }}</p>
                </div>
                <div class="space-y-1.5">
                    <span class="text-xs font-medium text-slate-500 uppercase">Registered Email</span>
                    <p class="text-slate-200 font-medium">{{ $student->user->email }}</p>
                </div>
                <div class="sm:col-span-2 space-y-1.5">
                    <span class="text-xs font-medium text-slate-500 uppercase">Residential Address</span>
                    <p class="text-slate-200 font-medium">{{ $student->address ?: 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Parent Info Widget -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5 text-violet-400"></i>
                Guardian Contacts
            </h2>

            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-lg bg-violet-500/10 flex items-center justify-center text-violet-400 shrink-0">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Guardian Name</span>
                        <p class="text-sm font-semibold text-slate-200 mt-0.5">{{ $student->parent_name }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 shrink-0">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Guardian Contact</span>
                        <p class="text-sm font-semibold text-slate-200 mt-0.5">{{ $student->parent_contact }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-lg bg-pink-500/10 flex items-center justify-center text-pink-400 shrink-0">
                        <i data-lucide="fingerprint" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Guardian NIC</span>
                        <p class="text-sm font-semibold text-slate-200 mt-0.5">{{ $student->parent_nic ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
