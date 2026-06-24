<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MediaLink;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        $materialsCount = Material::where('student_id', $student->id)->count();
        $mediaCount = MediaLink::where('student_id', $student->id)->count();
        
        // Latest payment status
        $currentMonth = Carbon::now()->format('Y-m');
        $latestPayment = Payment::where('student_id', $student->id)
            ->where('period_month', $currentMonth)
            ->first();

        return view('student.dashboard', compact('student', 'materialsCount', 'mediaCount', 'latestPayment'));
    }
}
