<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function create(Request $request)
    {
        $students = Student::orderBy('full_name')->get();
        $studentId = $request->query('studentId');
        return view('admin.materials.create', compact('students', 'studentId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:pdf,assignment',
            'file' => 'required|file|max:20480|mimes:pdf,doc,docx,jpg,jpeg,png',
            'week_date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $filePath = $file->storeAs('materials/' . $request->input('student_id'), $fileName, 'public');

        Material::create([
            'student_id' => $request->input('student_id'),
            'type' => $request->input('type'),
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'week_date' => $request->input('week_date'),
            'note' => $request->input('note'),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.students.show', $request->input('student_id'))->with('success', 'Material file uploaded successfully.');
    }

    public function destroy(Material $material)
    {
        $studentId = $material->student_id;
        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return redirect()->route('admin.students.show', $studentId)->with('success', 'Material file deleted.');
    }
}
