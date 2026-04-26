<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\LetterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;



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
    $request->validate([
        // ✅ Full name (at least 2 words)
        'name' => [
            'required',
            'string',
            'min:5',
            'regex:/^\w+\s+\w+/'
        ],

        // ✅ Email
        'email' => 'required|email:rfc,dns',

        // ✅ SA Phone
        'phone' => [
            'required',
            'regex:/^(\+27|0)[6-8][0-9]{8}$/'
        ],
    ], [
        'name.regex' => 'Please enter your full name (name and surname).',
        'phone.regex' => 'Enter a valid SA number (0821234567 or +27821234567).',
    ]);

    // 🔍 Find user by email
    $user = User::where('email', $request->email)->first();

    if ($user) {
        // ✅ UPDATE EVERY LOGIN
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);
    } else {
        // ✅ CREATE NEW USER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('password'), // temp
            'role' => 'resident'
        ]);
    }

    // 🔐 LOGIN
    Auth::login($user);

    return redirect()->route('resident.dashboard');
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

 

}