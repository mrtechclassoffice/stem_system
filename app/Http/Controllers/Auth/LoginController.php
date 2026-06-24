<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminLoginAlert;
use App\Mail\StudentLoginAlert;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Send login alert email if student
            if ($user->role === 'student' && $user->student) {
                $time = now()->timezone('Asia/Colombo')->format('M j, Y h:i A');
                
                try {
                    // Alert admin
                    $adminEmail = env('ADMIN_EMAIL', 'admin@stem.local');
                    Mail::to($adminEmail)->send(new AdminLoginAlert(
                        $user->student->full_name,
                        $user->email,
                        $time
                    ));
                    
                    // Alert student
                    Mail::to($user->email)->send(new StudentLoginAlert(
                        $user->student->full_name,
                        $time
                    ));
                } catch (\Exception $e) {
                    logger()->error("Failed to send login emails: " . $e->getMessage());
                }
            }

            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('student.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid email or password. Please try again.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
