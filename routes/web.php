<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\ResidentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CouncilorController;

// ================= HOME =================
Route::get('/', fn() => view('home'))->name('home');

// ================= ROLE =================
Route::get('/role', [ResidentController::class, 'roleSelection'])->name('role');

// ================= LOGIN PAGES =================
Route::view('/resident/login', 'resident.login')->name('resident.login');
Route::view('/councilor/login', 'councilor.login')->name('councilor.login');

// ================= LOGIN (AJAX JSON) =================
Route::post('/resident/login', [ResidentController::class, 'login'])
    ->name('resident.login.post');

Route::post('/councilor/login', [CouncilorController::class, 'login'])
    ->name('councilor.login.post');

// ================= OTP =================
Route::post('/otp/verify', [ResidentController::class, 'verifyOtp'])
    ->name('otp.verify');

// ================= OPTIONAL RESEND OTP =================
Route::post('/otp/resend', function (Request $request) {
    return response()->json(['success' => true]);
})->name('otp.resend');


// ================= TEST MAIL =================
Route::get('/test-mail', function () {

    Mail::raw('Hello OTP Test', function ($message) {
        $message->to('yourgmail@gmail.com')
                ->subject('Test Email');
    });

    return 'Email sent';
});


// ================= AUTH PROTECTED =================
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

    // ================= COUNCILOR + ADMIN =================
    Route::middleware('role:councilor,admin')->group(function () {

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

    // ================= ADMIN =================
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