@extends('layouts.admin')

@section('title', 'Students')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-white">Students Directory</h1>
            <p class="text-slate-400 text-sm mt-0.5">Manage and provision student portal accounts.</p>
        </div>
        <a href="{{ route('admin.students.create') }}" class="flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 w-fit">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Add Student
        </a>
    </div>

    <!-- Search & Filters -->
    <form action="{{ route('admin.students.index') }}" method="GET" class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex flex-col md:flex-row gap-3">
        <div class="flex-1 relative">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by student name or email..." class="w-full bg-slate-800 border border-slate-700 rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
            <i data-lucide="search" class="w-4.5 h-4.5 text-slate-500 absolute left-3 top-3.5"></i>
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50">
                <option value="">Tuition status...</option>
                <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="unpaid" {{ $status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-150 flex-1 md:flex-initial">
                Filter
            </button>
            <a href="{{ route('admin.students.index') }}" class="bg-slate-950 border border-slate-800 hover:bg-slate-900 text-slate-400 hover:text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-150 flex-1 md:flex-initial text-center flex items-center justify-center">
                Reset
            </a>
        </div>
    </form>

    <!-- Students Grid / List -->
    @if($students->isEmpty())
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-12 text-center">
            <i data-lucide="users" class="w-12 h-12 mx-auto text-slate-700 mb-3"></i>
            <h3 class="text-white font-bold text-base">No students found</h3>
            <p class="text-slate-500 text-sm mt-1">Try resetting your search query or add a new student account.</p>
        </div>
    @else
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-350">
                    <thead class="bg-slate-800/40 text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">School</th>
                            <th class="px-6 py-4">Guardian Contact</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach($students as $st)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-800 overflow-hidden flex items-center justify-center shrink-0 border border-slate-700">
                                        @if($st->profile_picture_path)
                                            <img src="{{ asset('storage/' . $st->profile_picture_path) }}" alt="{{ $st->full_name }}" class="w-full h-full object-cover" />
                                        @else
                                            <i data-lucide="user" class="w-5 h-5 text-slate-600"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white">{{ $st->full_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $st->user->email }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-slate-300">{{ $st->school ?: '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-slate-300 font-medium">{{ $st->parent_name ?: '—' }}</p>
                                    @if($st->parent_contact)
                                        <p class="text-xs text-slate-500">{{ $st->parent_contact }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.students.show', $st->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-violet-400 hover:text-white hover:bg-violet-600/10 transition-all">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> View
                                        </a>
                                        <a href="{{ route('admin.students.edit', $st->id) }}" class="p-2 text-slate-500 hover:text-white hover:bg-slate-800 rounded-lg transition-all">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.students.destroy', $st->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this student and all of their files/records?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-500 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-all">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination Wrapper -->
            @if($students->hasPages())
                <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/50">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
