<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        $materials = Material::where('student_id', $student->id)
            ->orderBy('week_date', 'desc')
            ->get();

        return view('student.materials.index', compact('materials'));
    }

    public function download($id)
    {
        $student = auth()->user()->student;
        $material = Material::where('student_id', $student->id)->findOrFail($id);

        if (!Storage::disk('public')->exists($material->file_path)) {
            abort(404, 'File not found on server.');
        }

        return Storage::disk('public')->download($material->file_path, $material->file_name);
    }
}
