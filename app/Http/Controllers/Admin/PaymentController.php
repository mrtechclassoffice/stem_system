<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', Carbon::now()->format('Y-m'));
        $search = $request->input('search');

        $students = Student::with(['payments' => function ($q) use ($period) {
            $q->where('period_month', $period);
        }])->orderBy('full_name');

        if ($search) {
            $students->where('full_name', 'like', "%{$search}%");
        }

        $students = $students->get();

        return view('admin.payments.index', compact('students', 'period', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:paid,unpaid,pending',
            'period_month' => 'required|regex:/^\d{4}-\d{2}$/',
            'paid_on' => 'nullable|date',
            'note' => 'nullable|string|max:255',
        ]);

        Payment::create([
            'student_id' => $request->input('student_id'),
            'amount' => $request->input('amount'),
            'status' => $request->input('status'),
            'period_month' => $request->input('period_month'),
            'paid_on' => $request->input('status') === 'paid' ? ($request->input('paid_on') ?? now()) : null,
            'note' => $request->input('note'),
        ]);

        return redirect()->back()->with('success', 'Payment status logged successfully.');
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:paid,unpaid,pending',
            'paid_on' => 'nullable|date',
            'note' => 'nullable|string|max:255',
        ]);

        $payment->update([
            'amount' => $request->input('amount'),
            'status' => $request->input('status'),
            'paid_on' => $request->input('status') === 'paid' ? ($request->input('paid_on') ?? now()) : null,
            'note' => $request->input('note'),
        ]);

        return redirect()->back()->with('success', 'Payment status updated.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->back()->with('success', 'Payment record deleted.');
    }
}
