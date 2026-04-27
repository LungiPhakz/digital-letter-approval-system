<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ResidentController extends Controller
{
    // ================= ROLE =================
    public function roleSelection()
    {
        return view('role');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ================= LOGIN (EMAIL ONLY OTP) =================
    public function login(Request $request)
    {
        $request->validate([
            'name'  => ['required','string','min:5','regex:/^\w+\s+\w+/'],
            'email' => 'required|email',
            'phone' => ['required','regex:/^(\+27|0)[6-8][0-9]{8}$/'],
        ]);

        $phone = $this->formatPhone($request->phone);

        // 🔍 Find user ONLY by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // CREATE NEW USER
            $user = User::create([
                'name' => trim($request->name),
                'email' => $request->email,
                'phone' => $phone,
                'password' => Hash::make('password'),
                'role' => 'resident'
            ]);
        } else {
            // OPTIONAL: update phone + name (NO verification anymore)
            $user->update([
                'name' => trim($request->name),
                'phone' => $phone,
            ]);
        }

        // ================= OTP =================
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(5)
        ]);

        // 📧 EMAIL OTP ONLY
        Mail::raw(
            "CommunityLetters: Your OTP code is $otp. It expires in 5 minutes.",
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('CommunityLetters OTP Verification');
            }
        );

        session(['otp_user_id' => $user->id]);

        return response()->json([
            'success' => true
        ]);
    }

    // ================= VERIFY OTP =================
   public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6'
    ]);

    $userId = session('otp_user_id');

    if (!$userId) {
        return response()->json([
            'success' => false,
            'message' => 'Session expired. Please login again.'
        ], 401);
    }

    $user = User::find($userId);
    // 🔥 DEBUG OTP (ADD HERE TEMPORARILY)
\Log::info('OTP DEBUG', [
    'otp' => $user->otp_code,
    'expires_at' => $user->otp_expires_at,
    'now' => now()
]);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    // ✅ FIX 1: CHECK EXPIRY FIRST (MOST IMPORTANT)
    if (!$user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
        return response()->json([
            'success' => false,
            'message' => 'OTP expired. Please resend OTP.'
        ], 422);
    }

    // ✅ FIX 2: CHECK OTP
    if ((string) $request->otp !== (string) $user->otp_code) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid OTP'
        ], 422);
    }

    // ✅ SUCCESS
    $user->update([
        'otp_code' => null,
        'otp_expires_at' => null
    ]);

    Auth::login($user);
    session()->forget('otp_user_id');

    return response()->json([
        'success' => true,
        'redirect' => route('resident.dashboard')
    ]);
}

public function resendOtp(Request $request)
{
    $userId = session('otp_user_id');

    if (!$userId) {
        return response()->json([
            'success' => false,
            'message' => 'Session expired'
        ], 401);
    }

    $user = User::find($userId);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    $otp = (string) rand(100000, 999999);

    $user->update([
        'otp_code' => $otp,
        'otp_expires_at' => now()->addMinutes(5)
    ]);

    // send email again
    Mail::raw("CommunityLetters: Your OTP is $otp. It expires in 5 minutes.", function ($message) use ($user) {
        $message->to($user->email)
            ->subject('OTP Resend');
    });

    return response()->json([
        'success' => true,
        'message' => 'OTP resent successfully'
    ]);
}
    // ================= OTP PAGE =================
    public function otpForm()
    {
        return view('auth.otp');
    }

    // ================= DASHBOARD =================
   public function dashboard()
{
    $user = Auth::user();

    $allRequests = LetterRequest::with('user')
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    return view('resident.dashboard', compact('user', 'allRequests'));
}
    

    // ================= PHONE FORMAT =================
    private function formatPhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '+27' . substr($phone, 1);
        }

        if (str_starts_with($phone, '27')) {
            return '+' . $phone;
        }

        return $phone;
    }
}