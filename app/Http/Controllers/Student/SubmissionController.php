<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        $submissions = Submission::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.submissions.index', compact('submissions'));
    }

    public function store(Request $request)
    {
        $student = auth()->user()->student;

        $request->validate([
            'file' => 'required|file|max:20480|mimes:pdf,doc,docx,jpg,jpeg,png',
            'note' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $filePath = $file->storeAs('submissions/' . $student->id, $fileName, 'public');

        Submission::create([
            'student_id' => $student->id,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'note' => $request->input('note'),
        ]);

        return redirect()->route('student.submissions.index')->with('success', 'Homework file submitted successfully.');
    }

    public function destroy($id)
    {
        $student = auth()->user()->student;
        $submission = Submission::where('student_id', $student->id)->findOrFail($id);

        Storage::disk('public')->delete($submission->file_path);
        $submission->delete();

        return redirect()->route('student.submissions.index')->with('success', 'Submission removed.');
    }
}
