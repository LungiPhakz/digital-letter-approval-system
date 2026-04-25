<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CouncilorController;

// ================= HOME =================
Route::get('/', function () {
    return view('home');
})->name('home');

// ================= ROLE SELECTION =================
Route::get('/role', [ResidentController::class, 'roleSelection'])->name('role');

// ================= LOGIN PAGES =================
Route::get('/resident/login', fn() => view('resident.login'))->name('resident.login');
Route::get('/councilor/login', fn() => view('councilor.login'))->name('councilor.login');

// ================= LOGIN HANDLERS =================
Route::post('/resident/login', [ResidentController::class, 'login'])->name('resident.login.post');
Route::post('/councilor/login', [CouncilorController::class, 'login'])->name('councilor.login.post');

// ================= AUTH PROTECTED =================
Route::middleware(['auth'])->group(function () {

    // ===== GENERAL DASHBOARD =====
    Route::get('/dashboard', [ResidentController::class, 'dashboard'])->name('dashboard');

    // ===== RESIDENT =====
    Route::middleware('role:resident')->group(function () {

        Route::get('/resident/dashboard', [ResidentController::class, 'dashboard'])
            ->name('resident.dashboard');

        Route::get('/resident/request-letter', [RequestController::class,'create'])
            ->name('resident.request.create');

        Route::post('/resident/request-letter', [RequestController::class,'store'])
            ->name('resident.request.store');
    });


    Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('role');
})->name('logout');

    // ===== COUNCILOR =====
    Route::middleware('role:councilor,admin')->group(function () {

        Route::get('/councilor/dashboard', [CouncilorController::class, 'dashboard'])
            ->name('councilor.dashboard');

        Route::post('/councilor/approve/{id}', [CouncilorController::class, 'approve'])
            ->name('councilor.approve');

        Route::post('/councilor/reject/{id}', [CouncilorController::class, 'reject'])
            ->name('councilor.reject');

       // ✅ FIXED: KEEP IT INSIDE GROUP
        Route::post('/councilor/approve-with-signature/{id}', [CouncilorController::class, 'approveWithSignature'])
            ->name('councilor.approve.signature');

        Route::post('/councilor/send/{id}', [CouncilorController::class, 'send'])
            ->name('councilor.send');
    });

     

    // ===== ADMIN =====
    Route::middleware('role:admin')->group(function () {

        Route::get('/admin/dashboard', [AdminController::class,'dashboard'])
            ->name('admin.dashboard');

        Route::get('/admin/requests', [AdminController::class,'requests'])
            ->name('admin.requests');

        Route::post('/admin/approve/{id}', [AdminController::class,'approve'])
            ->name('admin.approve');

        Route::post('/admin/reject/{id}', [AdminController::class,'reject'])
            ->name('admin.reject');
    });

});