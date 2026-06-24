@extends('layouts.admin')

@section('title', $student->full_name)

@section('content')
<div class="max-w-4xl space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.students.index') }}" class="text-slate-500 hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-slate-800 overflow-hidden flex items-center justify-center shrink-0 border border-slate-700">
                    @if($student->profile_picture_path)
                        <img src="{{ asset('storage/' . $student->profile_picture_path) }}" alt="{{ $student->full_name }}" class="w-full h-full object-cover" />
                    @else
                        <i data-lucide="user" class="w-6 h-6 text-slate-650"></i>
                    @endif
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">{{ $student->full_name }}</h1>
                    <p class="text-slate-400 text-sm">{{ $student->user->email }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.materials.create', ['studentId' => $student->id]) }}" class="flex items-center gap-1.5 bg-violet-650 hover:bg-violet-600 border border-violet-550 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition-all">
                <i data-lucide="upload" class="w-3.5 h-3.5"></i> Upload Material
            </a>
            <a href="{{ route('admin.media.create', ['studentId' => $student->id]) }}" class="flex items-center gap-1.5 bg-slate-900 border border-slate-800 text-slate-300 hover:text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition-all">
                <i data-lucide="link" class="w-3.5 h-3.5"></i> Share Media
            </a>
            <a href="{{ route('admin.students.edit', $student->id) }}" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl border border-transparent hover:border-slate-750 transition-all">
                <i data-lucide="pencil" class="w-4.5 h-4.5"></i>
            </a>
        </div>
    </div>

    <!-- Tabs Header -->
    <div class="flex gap-1 bg-slate-900 border border-slate-800 rounded-xl p-1 w-fit">
        <button onclick="switchTab('info')" id="tab-btn-info" class="tab-btn px-4 py-1.5 rounded-lg text-sm font-medium transition-all bg-violet-600 text-white">Profile</button>
        <button onclick="switchTab('materials')" id="tab-btn-materials" class="tab-btn px-4 py-1.5 rounded-lg text-sm font-medium transition-all text-slate-400 hover:text-white">Materials ({{ $student->materials->count() }})</button>
        <button onclick="switchTab('media')" id="tab-btn-media" class="tab-btn px-4 py-1.5 rounded-lg text-sm font-medium transition-all text-slate-400 hover:text-white">Media ({{ $student->mediaLinks->count() }})</button>
        <button onclick="switchTab('payments')" id="tab-btn-payments" class="tab-btn px-4 py-1.5 rounded-lg text-sm font-medium transition-all text-slate-400 hover:text-white">Payments ({{ $student->payments->count() }})</button>
    </div>

    <!-- Tab Contents -->
    
    <!-- Tab: Info -->
    <div id="tab-content-info" class="tab-content grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-4">
            <h2 class="text-white font-semibold text-sm">Personal Details</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-500 mb-0.5">Age</p>
                    <p class="text-sm text-white font-medium">{{ $student->age ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-0.5">Birthday</p>
                    <p class="text-sm text-white font-medium">{{ $student->birthday ? $student->birthday->format('M j, Y') : '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-0.5">School</p>
                    <p class="text-sm text-white font-medium">{{ $student->school ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-0.5">Address</p>
                    <p class="text-sm text-white font-medium">{{ $student->address ?: '—' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-4">
            <h2 class="text-white font-semibold text-sm">Parent / Guardian</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-slate-500 mb-0.5">Name</p>
                    <p class="text-sm text-white font-medium">{{ $student->parent_name ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-0.5">NIC Number</p>
                    <p class="text-sm text-white font-medium">{{ $student->parent_nic ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-0.5">Contact Number</p>
                    <p class="text-sm text-white font-medium">{{ $student->parent_contact ?: '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Materials -->
    <div id="tab-content-materials" class="tab-content hidden space-y-4">
        @if($student->materials->isEmpty())
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center text-slate-500 text-sm">
                <i data-lucide="folder-open" class="w-10 h-10 mx-auto text-slate-700 mb-2"></i>
                <p>No materials shared yet. Click "Upload Material" to share PDFs or assignments.</p>
            </div>
        @else
            @php
                $groupedMaterials = $student->materials->groupBy(function($m) {
                    return $m->week_date->format('Y-m-d');
                });
            @endphp
            @foreach($groupedMaterials as $week => $items)
                <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                    <div class="flex items-center gap-2 px-5 py-3 border-b border-slate-800 bg-slate-800/40">
                        <i data-lucide="calendar" class="w-4 h-4 text-violet-400"></i>
                        <h3 class="text-sm font-semibold text-white">
                            Week of {{ Carbon\Carbon::parse($week)->format('F j, Y') }}
                        </h3>
                        <span class="text-xs text-slate-550 ml-auto">{{ $items->count() }} item(s)</span>
                    </div>
                    <div class="divide-y divide-slate-800">
                        @foreach($items as $m)
                            <div class="flex items-center gap-3 px-5 py-3.5">
                                <i data-lucide="{{ $m->type === 'pdf' ? 'file-text' : 'file' }}" class="w-4.5 h-4.5 text-slate-500 shrink-0"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-white font-medium truncate">{{ $m->file_name }}</p>
                                    @if($m->note)
                                        <p class="text-xs text-slate-500 truncate mt-0.5">{{ $m->note }}</p>
                                    @endif
                                </div>
                                <span class="text-[10px] bg-slate-800 text-slate-400 px-2 py-0.5 rounded-full font-medium uppercase shrink-0">{{ $m->type }}</span>
                                <a href="{{ asset('storage/' . $m->file_path) }}" download="{{ $m->file_name }}" target="_blank" class="p-1.5 text-slate-500 hover:text-blue-450 hover:bg-blue-500/10 rounded-lg transition-all shrink-0">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.materials.destroy', $m->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this material file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-500 hover:text-red-450 hover:bg-red-500/10 rounded-lg transition-all shrink-0">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Tab: Media -->
    <div id="tab-content-media" class="tab-content hidden space-y-4">
        @if($student->mediaLinks->isEmpty())
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center text-slate-500 text-sm">
                <i data-lucide="link-2" class="w-10 h-10 mx-auto text-slate-700 mb-2"></i>
                <p>No Google Drive links shared yet. Click "Share Media" to paste links.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($student->mediaLinks as $media)
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center shrink-0 border border-slate-700">
                                <i data-lucide="{{ $media->type === 'photo' ? 'image' : 'video' }}" class="w-5 h-5 text-violet-400"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-500">{{ $media->week_date->format('M j, Y') }}</span>
                                    <span class="text-[10px] bg-slate-800 text-slate-450 px-2 py-0.5 rounded-full font-semibold uppercase">{{ $media->type }}</span>
                                </div>
                                <p class="text-sm font-semibold text-white mt-1 break-all select-all">{{ $media->drive_url }}</p>
                                @if($media->note)
                                    <p class="text-xs text-slate-400 mt-1">{{ $media->note }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end md:self-center shrink-0">
                            <a href="{{ $media->drive_url }}" target="_blank" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 transition-colors border border-slate-750">
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Open Drive
                            </a>
                            <button onclick="openEditMediaModal({{ json_encode($media) }})" class="p-2 text-slate-500 hover:text-white hover:bg-slate-800 rounded-xl transition-all">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('admin.media.destroy', $media->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this shared media link?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-500 hover:text-red-400 hover:bg-red-400/10 rounded-xl transition-all">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Tab: Payments -->
    <div id="tab-content-payments" class="tab-content hidden space-y-4">
        <!-- Add Payment Log Button -->
        <div class="flex justify-end">
            <button onclick="openPaymentModal()" class="flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-colors">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Log Payment
            </button>
        </div>

        @if($student->payments->isEmpty())
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center text-slate-500 text-sm">
                <i data-lucide="credit-card" class="w-10 h-10 mx-auto text-slate-700 mb-2"></i>
                <p>No billing records logged yet.</p>
            </div>
        @else
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-sm text-slate-350">
                    <thead class="bg-slate-800/40 text-slate-400 text-xs font-semibold uppercase border-b border-slate-800">
                        <tr>
                            <th class="px-5 py-3">Period</th>
                            <th class="px-5 py-3">Amount</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 hidden md:table-cell">Paid On</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach($student->payments as $p)
                            <tr class="hover:bg-slate-800/20 transition-colors">
                                <td class="px-5 py-3 text-white font-medium">{{ $p->period_month }}</td>
                                <td class="px-5 py-3 text-white font-medium">Rs. {{ number_format($p->amount, 2) }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider
                                        {{ $p->status === 'paid' ? 'bg-emerald-500/10 text-emerald-400' : ($p->status === 'pending' ? 'bg-amber-500/10 text-amber-400' : 'bg-red-500/10 text-red-400') }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-slate-400 hidden md:table-cell">
                                    {{ $p->paid_on ? $p->paid_on->format('M j, Y') : '—' }}
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button onclick="openEditPaymentModal({{ json_encode($p) }})" class="p-1.5 text-slate-500 hover:text-white hover:bg-slate-800 rounded-lg transition-all">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                        <form action="{{ route('admin.payments.destroy', $p->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this payment log?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-500 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-all">
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
        @endif
    </div>
</div>

<!-- Modal: Log Payment -->
<div id="payment-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen p-4 relative">
        <div onclick="closePaymentModal()" class="absolute inset-0 bg-black/60"></div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-md relative z-10 text-slate-100">
            <h2 class="text-lg font-bold text-white mb-4">Log Tuition Payment</h2>
            <form action="{{ route('admin.payments.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}" />
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Month Period *</label>
                    <input type="month" name="period_month" required value="{{ date('Y-m') }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Amount *</label>
                    <input type="number" name="amount" required step="0.01" value="0.00" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Payment Status *</label>
                    <select name="status" id="payment-status-select" onchange="togglePaidOn(this.value)" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50">
                        <option value="unpaid">Unpaid</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

                <div id="paid-on-wrapper" class="space-y-1.5 hidden">
                    <label class="block text-xs font-semibold text-slate-400">Paid Date</label>
                    <input type="date" name="paid_on" value="{{ date('Y-m-d') }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-violet-500/50" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Notes / Remarks</label>
                    <input type="text" name="note" placeholder="Cash, bank transfer reference..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none" />
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closePaymentModal()" class="flex-1 py-2 px-4 rounded-xl text-sm font-semibold bg-slate-800 text-slate-300 hover:bg-slate-700 transition-colors">Cancel</button>
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

<!-- Modal: Edit Media Link -->
<div id="edit-media-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen p-4 relative">
        <div onclick="closeEditMediaModal()" class="absolute inset-0 bg-black/60"></div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-md relative z-10 text-slate-100">
            <h2 class="text-lg font-bold text-white mb-4">Edit Shared Media Link</h2>
            <form id="edit-media-form" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Media Type *</label>
                    <select name="type" id="edit-media-type" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none">
                        <option value="photo">Photo</option>
                        <option value="video">Video</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Google Drive URL *</label>
                    <input type="url" name="drive_url" id="edit-media-url" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Class Date / Week *</label>
                    <input type="date" name="week_date" id="edit-media-date" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Note / Caption</label>
                    <input type="text" name="note" id="edit-media-note" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none" />
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditMediaModal()" class="flex-1 py-2 px-4 rounded-xl text-sm font-semibold bg-slate-800 text-slate-300 hover:bg-slate-700 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 py-2 px-4 rounded-xl text-sm font-semibold bg-violet-600 hover:bg-violet-500 text-white transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tab switcher
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('bg-violet-600', 'text-white');
            el.classList.add('text-slate-400', 'hover:text-white');
        });
        
        document.getElementById('tab-content-' + tabId).classList.remove('hidden');
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        activeBtn.classList.remove('text-slate-400', 'hover:text-white');
        activeBtn.classList.add('bg-violet-600', 'text-white');
    }

    // Modal Payment logs logic
    function openPaymentModal() {
        document.getElementById('payment-modal').classList.remove('hidden');
    }
    function closePaymentModal() {
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

    // Modal Edit Payment logic
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

    // Modal Edit Media Link logic
    function openEditMediaModal(media) {
        const modal = document.getElementById('edit-media-modal');
        const form = document.getElementById('edit-payment-form');
        
        document.getElementById('edit-media-form').action = `/admin/media/${media.id}`;
        document.getElementById('edit-media-type').value = media.type;
        document.getElementById('edit-media-url').value = media.drive_url;
        document.getElementById('edit-media-date').value = media.week_date.substring(0, 10);
        document.getElementById('edit-media-note').value = media.note || '';
        
        modal.classList.remove('hidden');
    }
    function closeEditMediaModal() {
        document.getElementById('edit-media-modal').classList.add('hidden');
    }
</script>
@endsection
