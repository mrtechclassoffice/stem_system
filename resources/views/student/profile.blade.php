@extends('layouts.student')

@section('title', 'My Profile & Billing')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in">
    <!-- Left Column: Academic Profile Card -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <!-- Decorative Header -->
            <div class="h-24 bg-gradient-to-r from-violet-600 via-indigo-600 to-violet-700 relative">
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:16px_16px]"></div>
            </div>

            <!-- Profile Info Body -->
            <div class="p-6 relative">
                <!-- Avatar Frame -->
                <div class="absolute -top-16 left-6">
                    @if($student->profile_picture_path)
                        <img src="{{ asset('storage/' . $student->profile_picture_path) }}" alt="{{ $student->full_name }}" class="w-24 h-24 rounded-2xl object-cover border-4 border-slate-900 shadow-lg">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-indigo-600/30 border-4 border-slate-900 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                            {{ strtoupper(substr($student->full_name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="pt-10 space-y-5">
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">{{ $student->full_name }}</h1>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 uppercase tracking-wider inline-block mt-1">
                            Student Account
                        </span>
                    </div>

                    <!-- Profile fields -->
                    <div class="space-y-4 pt-4 border-t border-slate-800/80">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Student ID</span>
                            <span class="text-slate-300 font-semibold">#STEM-{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Age / Birthday</span>
                            <span class="text-slate-300 font-semibold">{{ $student->age }} years ({{ $student->birthday ? \Carbon\Carbon::parse($student->birthday)->format('M d, Y') : 'N/A' }})</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">School Name</span>
                            <span class="text-slate-300 font-semibold max-w-[160px] truncate text-right" title="{{ $student->school }}">{{ $student->school ?: 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">System Email</span>
                            <span class="text-slate-300 font-semibold">{{ $student->user->email }}</span>
                        </div>
                        <div class="flex flex-col gap-1 text-xs">
                            <span class="text-slate-500 font-medium">Home Address</span>
                            <span class="text-slate-300 font-medium mt-0.5 leading-relaxed">{{ $student->address ?: 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guardian Card -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                <i data-lucide="shield" class="w-4 h-4 text-violet-400"></i>
                Emergency / Guardian Info
            </h3>

            <div class="space-y-4 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Parent/Guardian</span>
                    <span class="text-slate-300 font-semibold">{{ $student->parent_name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Contact Phone</span>
                    <span class="text-slate-300 font-semibold">{{ $student->parent_contact }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 font-medium">NIC Identification</span>
                    <span class="text-slate-300 font-semibold">{{ $student->parent_nic ?: 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Tuition Ledger -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i data-lucide="receipt" class="text-emerald-400 w-5 h-5"></i>
                        Tuition Billing Ledger
                    </h2>
                    <p class="text-slate-400 text-xs mt-0.5">Track your monthly tuition fees, invoice status, and receipt notes.</p>
                </div>
            </div>

            @if($student->payments->isEmpty())
                <div class="text-center py-12 flex flex-col items-center justify-center">
                    <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center text-slate-600 mb-3">
                        <i data-lucide="receipt" class="w-6 h-6"></i>
                    </div>
                    <p class="text-slate-400 text-sm">No payment records found.</p>
                    <p class="text-slate-500 text-xs mt-1">If you have recently made a payment, your instructor will update it here soon.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-medium">
                                <th class="pb-3 pr-2">Billing Month</th>
                                <th class="pb-3 px-2">Amount</th>
                                <th class="pb-3 px-2">Status</th>
                                <th class="pb-3 px-2">Paid On</th>
                                <th class="pb-3 pl-2">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($student->payments as $payment)
                                <tr>
                                    <!-- Billing Month -->
                                    <td class="py-4 pr-2 font-bold text-slate-200">
                                        {{ \Carbon\Carbon::parse($payment->period_month . '-01')->format('F Y') }}
                                    </td>
                                    
                                    <!-- Amount -->
                                    <td class="py-4 px-2 font-semibold text-slate-300">
                                        ${{ number_format($payment->amount, 2) }}
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="py-4 px-2">
                                        @if($payment->status === 'paid')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                Paid
                                            </span>
                                        @elseif($payment->status === 'pending')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                Unpaid
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Paid On -->
                                    <td class="py-4 px-2 text-slate-400 font-medium">
                                        {{ $payment->paid_on ? \Carbon\Carbon::parse($payment->paid_on)->format('M d, Y') : '—' }}
                                    </td>
                                    
                                    <!-- Notes/Remarks -->
                                    <td class="py-4 pl-2 text-slate-500 italic max-w-[150px] truncate" title="{{ $payment->note }}">
                                        {{ $payment->note ?: 'No remarks' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
