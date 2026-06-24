<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function show()
    {
        $student = auth()->user()->student->load(['payments' => function ($q) {
            $q->orderBy('period_month', 'desc');
        }]);

        return view('student.profile', compact('student'));
    }
}
