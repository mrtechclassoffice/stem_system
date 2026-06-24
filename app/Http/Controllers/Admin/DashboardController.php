<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Material;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $studentsCount = Student::count();
        
        // Recent uploads (materials)
        $recentMaterials = Material::with('student')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Payment summary for this month
        $currentMonth = Carbon::now()->format('Y-m');
        $payments = Payment::where('period_month', $currentMonth)->get();
        
        $paidCount = $payments->where('status', 'paid')->count();
        $unpaidCount = $payments->where('status', 'unpaid')->count();
        $pendingCount = $payments->where('status', 'pending')->count();
        
        if ($payments->isEmpty() && $studentsCount > 0) {
            $unpaidCount = $studentsCount;
        }

        return view('admin.dashboard', compact(
            'studentsCount',
            'recentMaterials',
            'paidCount',
            'unpaidCount',
            'pendingCount',
            'currentMonth'
        ));
    }
}
