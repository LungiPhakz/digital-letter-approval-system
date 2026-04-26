<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\LetterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;



class ResidentController extends Controller
{
    // Show Role selection & login page
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

    // Handle Resident login
public function login(Request $request)
{
    $phone = $this->formatPhone($request->phone);

    
    $request->validate([
        'name' => ['required','string','min:5','regex:/^\w+\s+\w+/'],
        'email' => 'required|email:rfc,dns',
        'phone' => ['required','regex:/^(\+27|0)[6-8][0-9]{8}$/'],
    ]);

    // 🔍 find or create user
    $user = User::where('email', $request->email)->first();


    if (!$user) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phone,
            'password' => Hash::make('password'),
            'role' => 'resident'
        ]);
    } else {
        // optional safety check
        if ($user->phone !== $request->phone) {
            return back()->with('error', 'Phone number does not match this account.');
        }
    }

    // 🔐 generate OTP
    $otp = rand(100000, 999999);

    $user->update([
        'otp_code' => $otp,
        'otp_expires_at' => now()->addMinutes(5)
    ]);

    // 📧 EMAIL OTP
    Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
        $message->to($user->email)
            ->subject('CommunityLetters OTP Verification');
    });

    // 📱 SMS OTP (replace with Termii / Twilio / Africa's Talking)
    Http::post('https://your-sms-api.com/send', [
        'to' => $user->phone,
        'message' => "Your OTP is $otp"
    ]);

    // 🚪 store user id temporarily
    session(['otp_user_id' => $user->id]);

    // ❌ DO NOT login yet
    return redirect()->route('otp.form');
}
    // Show Resident dashboard
   public function dashboard()
{
    $user = Auth::user();

    $requests = LetterRequest::with('user')
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    $allRequests = $requests;

    return view('resident.dashboard', compact('user', 'requests', 'allRequests'));
}

public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required'
    ]);

    $user = User::find(session('otp_user_id'));

    if (!$user) {
        return redirect()->route('role')->with('error', 'Session expired');
    }

    // ❌ invalid or expired OTP
    if (
        $user->otp_code !== $request->otp ||
        now()->greaterThan($user->otp_expires_at)
    ) {
        return back()->with('error', 'Invalid or expired OTP');
    }

    // ✅ clear OTP
    $user->update([
        'otp_code' => null,
        'otp_expires_at' => null
    ]);

    // 🔐 login user
    Auth::login($user);

    session()->forget('otp_user_id');

    return redirect()->route('resident.dashboard');
}

 public function otpForm()
{
    return view('auth.otp');
}

private function formatPhone($phone)
{
    // remove spaces and symbols
    $phone = preg_replace('/\s+/', '', $phone);

    // if starts with 0 → convert to +27
    if (str_starts_with($phone, '0')) {
        return '+27' . substr($phone, 1);
    }

    return $phone;
}

}