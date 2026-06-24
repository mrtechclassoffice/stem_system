<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaLink;
use App\Models\Student;
use Illuminate\Http\Request;

class MediaLinkController extends Controller
{
    public function create(Request $request)
    {
        $students = Student::orderBy('full_name')->get();
        $studentId = $request->query('studentId');
        return view('admin.media.create', compact('students', 'studentId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:photo,video',
            'drive_url' => ['required', 'url', function ($attribute, $value, $fail) {
                if (!str_contains($value, 'drive.google.com')) {
                    $fail('The link must be a valid Google Drive URL containing drive.google.com');
                }
            }],
            'week_date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        MediaLink::create([
            'student_id' => $request->input('student_id'),
            'type' => $request->input('type'),
            'drive_url' => $request->input('drive_url'),
            'week_date' => $request->input('week_date'),
            'note' => $request->input('note'),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.students.show', $request->input('student_id'))->with('success', 'Google Drive media link shared.');
    }

    public function update(Request $request, $id)
    {
        $mediaLink = MediaLink::findOrFail($id);

        $request->validate([
            'type' => 'required|in:photo,video',
            'drive_url' => ['required', 'url', function ($attribute, $value, $fail) {
                if (!str_contains($value, 'drive.google.com')) {
                    $fail('The link must be a valid Google Drive URL containing drive.google.com');
                }
            }],
            'week_date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $mediaLink->update([
            'type' => $request->input('type'),
            'drive_url' => $request->input('drive_url'),
            'week_date' => $request->input('week_date'),
            'note' => $request->input('note'),
        ]);

        return redirect()->route('admin.students.show', $mediaLink->student_id)->with('success', 'Shared media link updated.');
    }

    public function destroy($id)
    {
        $mediaLink = MediaLink::findOrFail($id);
        $studentId = $mediaLink->student_id;
        $mediaLink->delete();

        return redirect()->route('admin.students.show', $studentId)->with('success', 'Shared media link deleted.');
    }
}
