@extends('layouts.admin')

@section('title', 'Add Student')

@section('content')
<div class="max-w-3xl">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.students.index') }}" class="text-slate-500 hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-white">Create Student Account</h1>
            <p class="text-slate-400 text-sm">Add personal details and credentials. A welcome email will be dispatched automatically.</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-3 text-red-400 text-sm space-y-1">
                <p class="font-semibold">Please fix the following validation errors:</p>
                <ul class="list-disc pl-5 text-xs">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-6">
            <!-- Basic Credentials -->
            <div>
                <h2 class="text-white font-semibold text-sm border-b border-slate-800 pb-2 mb-4">Portal Login Credentials</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-300">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="student@example.com" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-300">Login Password *</label>
                        <input type="text" name="password" value="{{ old('password', Str::random(10)) }}" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                    </div>
                </div>
            </div>

            <!-- Student Profile -->
            <div>
                <h2 class="text-white font-semibold text-sm border-b border-slate-800 pb-2 mb-4">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-medium text-slate-300">Full Name *</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="John Doe" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-300">Age</label>
                        <input type="number" name="age" value="{{ old('age') }}" placeholder="12" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-300">Birthday</label>
                        <input type="date" name="birthday" value="{{ old('birthday') }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-300">School</label>
                        <input type="text" name="school" value="{{ old('school') }}" placeholder="High School" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-300">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="123 Main St, City" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-medium text-slate-300">Profile Picture</label>
                        <input type="file" name="profile_picture" accept="image/*" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-violet-600 file:text-white hover:file:bg-violet-500 focus:outline-none" />
                    </div>
                </div>
            </div>

            <!-- Parent Details -->
            <div>
                <h2 class="text-white font-semibold text-sm border-b border-slate-800 pb-2 mb-4">Parent / Guardian Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-300">Parent Name</label>
                        <input type="text" name="parent_name" value="{{ old('parent_name') }}" placeholder="Jane Doe" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-300">Parent NIC</label>
                        <input type="text" name="parent_nic" value="{{ old('parent_nic') }}" placeholder="123456789V" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-300">Parent Contact</label>
                        <input type="text" name="parent_contact" value="{{ old('parent_contact') }}" placeholder="0771234567" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex gap-3">
            <a href="{{ route('admin.students.index') }}" class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-sm font-medium bg-slate-800 text-slate-300 hover:bg-slate-700 transition-colors text-center">
                Cancel
            </a>
            <button type="submit" class="flex-1 sm:flex-none bg-violet-600 hover:bg-violet-500 text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition-colors">
                Create Account
            </button>
        </div>
    </form>
</div>
@endsection
