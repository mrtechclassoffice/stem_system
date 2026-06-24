<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentWelcome;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Student::with('user');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $currentMonth = Carbon::now()->format('Y-m');
            $query->whereHas('payments', function ($q) use ($status, $currentMonth) {
                $q->where('period_month', $currentMonth)->where('status', $status);
            });
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.students.index', compact('students', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'age' => 'nullable|integer|min:1|max:100',
            'birthday' => 'nullable|date',
            'school' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'parent_name' => 'nullable|string|max:255',
            'parent_nic' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:5120',
        ]);

        $picPath = null;
        if ($request->hasFile('profile_picture')) {
            $picPath = $request->file('profile_picture')->store('profiles', 'public');
        }

        $user = User::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'student',
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'full_name' => $request->input('full_name'),
            'age' => $request->input('age'),
            'birthday' => $request->input('birthday'),
            'school' => $request->input('school'),
            'address' => $request->input('address'),
            'parent_name' => $request->input('parent_name'),
            'parent_nic' => $request->input('parent_nic'),
            'parent_contact' => $request->input('parent_contact'),
            'profile_picture_path' => $picPath,
        ]);

        try {
            Mail::to($user->email)->send(new StudentWelcome(
                $student->full_name,
                $user->email,
                $request->input('password')
            ));
        } catch (\Exception $e) {
            logger()->error("Failed to send welcome email: " . $e->getMessage());
        }

        return redirect()->route('admin.students.index')->with('success', 'Student account created and welcome email dispatched.');
    }

    public function show($id)
    {
        $student = Student::with(['user', 'materials', 'mediaLinks', 'payments'])->findOrFail($id);
        $students = Student::orderBy('full_name')->get();

        return view('admin.students.show', compact('student', 'students'));
    }

    public function edit(Student $student)
    {
        $student->load('user');
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'full_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'password' => 'nullable|string|min:8',
            'age' => 'nullable|integer|min:1|max:100',
            'birthday' => 'nullable|date',
            'school' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'parent_name' => 'nullable|string|max:255',
            'parent_nic' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($student->profile_picture_path) {
                Storage::disk('public')->delete($student->profile_picture_path);
            }
            $student->profile_picture_path = $request->file('profile_picture')->store('profiles', 'public');
        }

        $userUpdate = ['email' => $request->input('email')];
        if ($request->filled('password')) {
            $userUpdate['password'] = Hash::make($request->input('password'));
        }
        $student->user->update($userUpdate);

        $student->update([
            'full_name' => $request->input('full_name'),
            'age' => $request->input('age'),
            'birthday' => $request->input('birthday'),
            'school' => $request->input('school'),
            'address' => $request->input('address'),
            'parent_name' => $request->input('parent_name'),
            'parent_nic' => $request->input('parent_nic'),
            'parent_contact' => $request->input('parent_contact'),
        ]);

        return redirect()->route('admin.students.show', $student->id)->with('success', 'Student details updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->profile_picture_path) {
            Storage::disk('public')->delete($student->profile_picture_path);
        }

        foreach ($student->materials as $material) {
            Storage::disk('public')->delete($material->file_path);
        }
        
        foreach ($student->submissions as $submission) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $student->user->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student account and all associated records deleted.');
    }
}
