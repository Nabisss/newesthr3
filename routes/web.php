<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\ProfileController; // Add this line
use App\Http\Controllers\SettingsController; // Add this line too for settings

Route::get('/', [LandingController::class, 'main'])->name('landing');

Route::get('/sendotp', function () {
    return view('auth.otp');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/dashboard', [AuthController::class, 'showMainPage'])->name('dashboard');

// Profile route
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

// Settings route
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

// Logout (must be POST)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// For Successfully registered users
Route::get('/success', function () {
    return view('auth.success');
})->name('auth.success');

// Training Management Routes
Route::prefix('training')->group(function () {
    Route::get('/course-management', [TrainingController::class, 'courseManagement'])->name('training.course-management');
    Route::post('/course-management/enroll', [TrainingController::class, 'enrollCourse'])->name('training.enroll-course');
});

// Learning Management Routes
Route::prefix('learning')->group(function () {
    Route::get('/safety-training', [LearningController::class, 'safetyTraining'])->name('learning.safety-training');
    Route::post('/safety-training/enroll', [LearningController::class, 'enrollSafetyCourse'])->name('learning.enroll-safety-course');
    Route::get('/maintenance-inspection', [LearningController::class, 'maintenanceInspection'])->name('learning.maintenance-inspection');
});
