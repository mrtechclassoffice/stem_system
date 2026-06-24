@extends('layouts.admin')

@section('title', 'Payments Ledger')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-white">Tuition & Payment Tracker</h1>
        <p class="text-slate-400 text-sm mt-0.5">Track, edit, and log monthly fee records per student.</p>
    </div>

    <!-- Period & Search Filter -->
    <form action="{{ route('admin.payments.index') }}" method="GET" class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex flex-col md:flex-row gap-3">
        <div class="w-full md:w-56">
            <label class="block text-xs font-semibold text-slate-500 mb-1">Billing Month</label>
            <input type="month" name="period" value="{{ $period }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
        </div>
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 mb-1">Search Student</label>
            <div class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by student name..." class="w-full bg-slate-800 border border-slate-700 rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                <i data-lucide="search" class="w-4.5 h-4.5 text-slate-500 absolute left-3 top-3.5"></i>
            </div>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-slate-850 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl border border-slate-750 transition-all duration-150 flex-1 md:flex-initial">
                Filter
            </button>
            <a href="{{ route('admin.payments.index') }}" class="bg-slate-950 border border-slate-850 hover:bg-slate-900 text-slate-400 hover:text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-150 flex-1 md:flex-initial text-center flex items-center justify-center">
                Reset
            </a>
        </div>
    </form>

    <!-- Ledger Table -->
    @if($students->isEmpty())
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-12 text-center">
            <i data-lucide="credit-card" class="w-12 h-12 mx-auto text-slate-700 mb-3"></i>
            <h3 class="text-white font-bold text-base">No records found</h3>
            <p class="text-slate-550 text-sm">Please register students first to manage payments.</p>
        </div>
    @else
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-350">
                    <thead class="bg-slate-800/40 text-slate-400 text-xs font-semibold uppercase border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Tuition Status ({{ $period }})</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4 hidden md:table-cell">Paid On</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach($students as $st)
                            @php
                                $payment = $st->payments->first(); // filtered by period in controller query
                            @endphp
                            <tr class="hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-white">{{ $st->full_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $st->user->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($payment)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider
                                            {{ $payment->status === 'paid' ? 'bg-emerald-500/10 text-emerald-400' : ($payment->status === 'pending' ? 'bg-amber-500/10 text-amber-400' : 'bg-red-500/10 text-red-400') }}">
                                            {{ $payment->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-red-500/10 text-red-400">
                                            unpaid
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-white font-medium">
                                        {{ $payment ? 'Rs. ' . number_format($payment->amount, 2) : '—' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-slate-400 hidden md:table-cell">
                                    {{ $payment && $payment->paid_on ? $payment->paid_on->format('M j, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($payment)
                                        <button onclick="openEditPaymentModal({{ json_encode($payment) }})" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-violet-400 hover:text-white hover:bg-violet-600/10 transition-all ml-auto">
                                            <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit Log
                                        </button>
                                    @else
                                        <button onclick="openLogPaymentModal({{ $st->id }}, '{{ $st->full_name }}')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-400 hover:text-white hover:bg-emerald-600/10 transition-all ml-auto">
                                            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Log Fee
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Modal: Log Payment -->
<div id="payment-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen p-4 relative">
        <div onclick="closeLogPaymentModal()" class="absolute inset-0 bg-black/60"></div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-md relative z-10 text-slate-100">
            <h2 class="text-lg font-bold text-white mb-1">Log Tuition Payment</h2>
            <p class="text-xs text-slate-400 mb-4" id="modal-student-name"></p>
            <form action="{{ route('admin.payments.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="student_id" id="modal-student-id" />
                <input type="hidden" name="period_month" value="{{ $period }}" />
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Month Period</label>
                    <input type="text" disabled value="{{ $period }}" class="w-full bg-slate-855 border border-slate-800 rounded-xl px-4 py-2 text-sm text-slate-500 select-none cursor-not-allowed" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Amount *</label>
                    <input type="number" name="amount" required step="0.01" value="0.00" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Payment Status *</label>
                    <select name="status" id="payment-status-select" onchange="togglePaidOn(this.value)" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none">
                        <option value="unpaid">Unpaid</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

                <div id="paid-on-wrapper" class="space-y-1.5 hidden">
                    <label class="block text-xs font-semibold text-slate-400">Paid Date</label>
                    <input type="date" name="paid_on" value="{{ date('Y-m-d') }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Notes / Remarks</label>
                    <input type="text" name="note" placeholder="Cash, bank transfer reference..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none" />
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeLogPaymentModal()" class="flex-1 py-2 px-4 rounded-xl text-sm font-semibold bg-slate-800 text-slate-300 hover:bg-slate-700 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 py-2 px-4 rounded-xl text-sm font-semibold bg-violet-600 hover:bg-violet-500 text-white transition-colors">Save Logs</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Payment -->
<div id="edit-payment-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen p-4 relative">
        <div onclick="closeEditPaymentModal()" class="absolute inset-0 bg-black/60"></div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-md relative z-10 text-slate-100">
            <h2 class="text-lg font-bold text-white mb-4">Edit Tuition Payment</h2>
            <form id="edit-payment-form" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Amount *</label>
                    <input type="number" name="amount" id="edit-payment-amount" required step="0.01" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Payment Status *</label>
                    <select name="status" id="edit-payment-status" onchange="toggleEditPaidOn(this.value)" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50">
                        <option value="unpaid">Unpaid</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

                <div id="edit-paid-on-wrapper" class="space-y-1.5 hidden">
                    <label class="block text-xs font-semibold text-slate-400">Paid Date</label>
                    <input type="date" name="paid_on" id="edit-payment-paid-on" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Notes / Remarks</label>
                    <input type="text" name="note" id="edit-payment-note" placeholder="Cash, bank transfer reference..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none" />
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditPaymentModal()" class="flex-1 py-2 px-4 rounded-xl text-sm font-semibold bg-slate-800 text-slate-300 hover:bg-slate-700 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 py-2 px-4 rounded-xl text-sm font-semibold bg-violet-600 hover:bg-violet-500 text-white transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openLogPaymentModal(studentId, studentName) {
        document.getElementById('modal-student-id').value = studentId;
        document.getElementById('modal-student-name').innerText = "Logging for: " + studentName;
        document.getElementById('payment-modal').classList.remove('hidden');
    }
    function closeLogPaymentModal() {
        document.getElementById('payment-modal').classList.add('hidden');
    }
    function togglePaidOn(val) {
        const wrapper = document.getElementById('paid-on-wrapper');
        if (val === 'paid') {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
        }
    }

    function openEditPaymentModal(payment) {
        const modal = document.getElementById('edit-payment-modal');
        const form = document.getElementById('edit-payment-form');
        
        form.action = `/admin/payments/${payment.id}`;
        document.getElementById('edit-payment-amount').value = payment.amount;
        document.getElementById('edit-payment-status').value = payment.status;
        document.getElementById('edit-payment-note').value = payment.note || '';
        
        toggleEditPaidOn(payment.status);
        if (payment.paid_on) {
            document.getElementById('edit-payment-paid-on').value = payment.paid_on.substring(0, 10);
        }
        
        modal.classList.remove('hidden');
    }
    function closeEditPaymentModal() {
        document.getElementById('edit-payment-modal').classList.add('hidden');
    }
    function toggleEditPaidOn(val) {
        const wrapper = document.getElementById('edit-paid-on-wrapper');
        if (val === 'paid') {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
        }
    }
</script>
@endsection
