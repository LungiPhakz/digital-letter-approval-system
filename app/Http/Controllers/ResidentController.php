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

    // Handle Resident login
    public function login(Request $request)
    {
        $request->validate([
        'email' => 'required|email',
    ]);

    // 🔥 Find or create user
    $user = User::firstOrCreate(
        ['email' => $request->email],
        [
            'name' => $request->name,
            'password' => Hash::make('password'), // temp
            'role' => 'resident'
        ]
    );

    // 🔥 LOGIN USER
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