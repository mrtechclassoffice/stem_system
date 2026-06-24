<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterial;
use App\Http\Controllers\Admin\MediaLinkController as AdminMedia;
use App\Http\Controllers\Admin\PaymentController;

use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\MaterialController as StudentMaterial;
use App\Http\Controllers\Student\MediaController as StudentMedia;
use App\Http\Controllers\Student\SubmissionController;
use App\Http\Controllers\Student\ProfileController;

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        
        // Students CRUD
        Route::resource('students', StudentController::class);
        
        // Materials (PDFs/Assignments)
        Route::get('/materials/create', [AdminMaterial::class, 'create'])->name('materials.create');
        Route::post('/materials', [AdminMaterial::class, 'store'])->name('materials.store');
        Route::delete('/materials/{material}', [AdminMaterial::class, 'destroy'])->name('materials.destroy');
        
        // Media links (Google Drive URLs)
        Route::get('/media/create', [AdminMedia::class, 'create'])->name('media.create');
        Route::post('/media', [AdminMedia::class, 'store'])->name('media.store');
        Route::patch('/media/{media_link}', [AdminMedia::class, 'update'])->name('media.update');
        Route::delete('/media/{media_link}', [AdminMedia::class, 'destroy'])->name('media.destroy');
        
        // Payments
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::patch('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });

    // Student Routes
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
        
        // Materials (PDFs/Assignments)
        Route::get('/materials', [StudentMaterial::class, 'index'])->name('materials.index');
        Route::get('/materials/{material}/download', [StudentMaterial::class, 'download'])->name('materials.download');
        
        // Media Gallery
        Route::get('/media', [StudentMedia::class, 'index'])->name('media.index');
        
        // Submissions desk
        Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
        Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
        Route::delete('/submissions/{submission}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');
        
        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    });
});

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});
