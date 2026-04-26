<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class ResidentController extends Controller
{
    public function roleSelection()
    {
        return view('role');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ================= LOGIN =================
    public function login(Request $request)
    {
        $request->validate([
            'name' => ['required','string','min:5','regex:/^\w+\s+\w+/'],
            'email' => 'required|email',
            'phone' => ['required','regex:/^(\+27|0)[6-8][0-9]{8}$/'],
        ], [
            'name.regex' => 'Enter full name (name & surname)',
            'phone.regex' => 'Use valid SA number (082... or +2782...)'
        ]);

        $formattedPhone = $this->formatPhone($request->phone);

        // 🔍 Find user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => trim($request->name),
                'email' => $request->email,
                'phone' => $formattedPhone,
                'password' => Hash::make('password'),
                'role' => 'resident'
            ]);
        } else {
            // ✅ Compare with formatted phone
            if ($user->phone !== $formattedPhone) {
                return back()->with('error', 'Phone number does not match this account.');
            }
        }

        // ================= OTP =================
        $otp = (string) rand(100000, 999999);

        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(5)
        ]);

        // 📧 EMAIL
        try {
            Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('OTP Verification');
            });
        } catch (\Exception $e) {
            // email failed silently
        }

        // 📱 SMS (safe fallback)
        try {
            Http::post('https://your-sms-api.com/send', [
                'to' => $user->phone,
                'message' => "Your OTP is $otp"
            ]);
        } catch (\Exception $e) {
            // SMS failed silently
        }

        session(['otp_user_id' => $user->id]);

        return response()->json([
    'success' => true
]);
    }

    // ================= OTP VERIFY =================
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect()->route('role')->with('error', 'Session expired');
        }

        if (
            $user->otp_code !== $request->otp ||
            now()->gt($user->otp_expires_at)
        ) {
            return back()->with('error', 'Invalid or expired OTP');
        }

        // ✅ clear
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

    public function otpForm()
    {
        return view('auth.otp');
    }

    public function dashboard()
    {
        $user = Auth::user();

        $requests = LetterRequest::with('user')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('resident.dashboard', compact('user', 'requests'));
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