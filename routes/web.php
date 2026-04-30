<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ResidentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CouncilorController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// HOME
Route::get('/', fn() => view('home'))->name('home');

// STATIC PAGES
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-of-service', [PageController::class, 'terms'])->name('terms');
Route::get('/security', [PageController::class, 'security'])->name('security');

// ROLE SELECTION
Route::get('/role', [ResidentController::class, 'roleSelection'])->name('role');

// LOGIN PAGES
Route::view('/resident/login', 'resident.login')->name('resident.login');
Route::view('/councilor/login', 'councilor.login')->name('councilor.login');

// LOGIN ACTIONS
Route::post('/resident/login', [ResidentController::class, 'login'])->name('resident.login.post');
Route::post('/councilor/login', [CouncilorController::class, 'login'])->name('councilor.login.post');

// OTP
Route::post('/otp/verify', [ResidentController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp/resend', [ResidentController::class, 'resendOtp'])->name('otp.resend');

// PASSWORD RESET
Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('/reset-password/{token}', fn($token) =>
    view('auth.reset-password', ['token' => $token])
)->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // ================= RESIDENT =================
    Route::middleware('role:resident')->group(function () {

        Route::get('/resident/dashboard', [ResidentController::class, 'dashboard'])
            ->name('resident.dashboard');

        Route::get('/resident/request-letter', [RequestController::class, 'create'])
            ->name('resident.request.create');

        Route::post('/resident/request-letter', [RequestController::class, 'store'])
            ->name('resident.request.store');

        Route::post('/resident/request/{id}/cancel', [RequestController::class, 'cancel'])
            ->name('resident.request.cancel');
    });


    // ================= COUNCILOR + ADMIN + DEMO =================
    Route::middleware('role:councilor,admin,demo')->group(function () {

        Route::get('/councilor/dashboard', [CouncilorController::class, 'dashboard'])
            ->name('councilor.dashboard');

        Route::post('/councilor/approve/{id}', [CouncilorController::class, 'approve'])
            ->name('councilor.approve');

        Route::post('/councilor/reject/{id}', [CouncilorController::class, 'reject'])
            ->name('councilor.reject');

        Route::post('/councilor/approve-with-signature/{id}', [CouncilorController::class, 'approveWithSignature'])
            ->name('councilor.approve.signature');

        Route::post('/councilor/send/{id}', [CouncilorController::class, 'send'])
            ->name('councilor.send');

        Route::post('/councilor/update-credentials', [CouncilorController::class, 'updateCredentials'])
            ->name('councilor.update.credentials');

        Route::post('/councilor/request/delete/{id}', [CouncilorController::class, 'destroy'])
            ->name('councilor.request.delete');
    });


    // ================= ADMIN ONLY =================
    Route::middleware('role:admin')->group(function () {

        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');

        Route::get('/admin/requests', [AdminController::class, 'requests'])
            ->name('admin.requests');

        Route::post('/admin/approve/{id}', [AdminController::class, 'approve'])
            ->name('admin.approve');

        Route::post('/admin/reject/{id}', [AdminController::class, 'reject'])
            ->name('admin.reject');
    });


    // ================= LOGOUT =================
    Route::post('/logout', function (Request $request) {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');

    })->name('logout');

});