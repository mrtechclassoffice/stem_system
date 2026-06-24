<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MediaLink;

class MediaController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        $mediaLinks = MediaLink::where('student_id', $student->id)
            ->orderBy('week_date', 'desc')
            ->get();

        return view('student.media.index', compact('mediaLinks'));
    }
}
