<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\LetterRequest;
use Illuminate\Support\Facades\DB;

class CouncilorController extends Controller
{
    // ================= LOGIN =================
    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {

        $user = Auth::user();
        session()->regenerate();

        // 🔒 Role check
        if (!in_array($user->role, ['councilor', 'admin'])) {
            Auth::logout();

            return back()->with('error', 'Access denied.');
        }

        return redirect()->route('councilor.dashboard');
    }

    // ❌ Wrong email OR password (don’t reveal which one for security)
    return back()->with('error', 'Invalid Login Details');
}

    // ================= DASHBOARD =================
   public function dashboard(Request $request)
{
    $query = LetterRequest::with('user');

    // Search by reference number OR name (safer)
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('reference_number', 'like', '%' . $request->search . '%')
              ->orWhere('name', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->from_date) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->to_date) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $requests = $query->latest()->get();

    return view('councilor.dashboard', compact('requests'));
}

    // ================= APPROVE (NO SIGNATURE) =================
    public function approve($id)
    {
         $request = LetterRequest::findOrFail($id);

         $request->update([
            'status' => 'Approved',
            'approved_by' => auth()->user()->name,
            'approved_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    // ================= REJECT =================
    public function reject(Request $req, $id)
    {
         $request = LetterRequest::findOrFail($id);

        $request->update([
            'status' => 'Rejected',
            'rejection_reason' => $req->reason
        ]);

        return response()->json(['success' => true]);
    }

    // ================= APPROVE WITH SIGNATURE + STAMP =================
 // ================= APPROVE WITH SIGNATURE + STAMP =================
   public function approveWithSignature(Request $request, $id)
{
    try {
        $req = LetterRequest::findOrFail($id);

        // ===== SIGNATURE =====
   if ($request->signature === '/images/default-signature.png') {
    $req->signed_letter = asset('images/default-signature.png');
} else {
    $image = str_replace('data:image/png;base64,', '', $request->signature);
    $image = str_replace(' ', '+', $image);

    $fileName = 'signatures/sign_' . time() . '_' . uniqid() . '.png';

    Storage::disk('public')->put($fileName, base64_decode($image));

    $req->signed_letter = 'storage/' . $fileName;
}

        // ===== STAMP =====
        if ($request->hasFile('stamp')) {

            $path = $request->file('stamp')->store('stamps', 'public');
            $req->stamp = 'storage/' . $path; // ✅ just store path

        } else {
            $req->stamp = asset('images/default-stamp.png');
        }

        // ===== APPROVAL =====
        $req->status = 'Approved';
        $req->approved_at = now();
        $req->approved_by = auth()->user()->name;
        $req->save();

        return response()->json([
            'success' => true,
            'signed_letter' => $req->signed_letter,
            'stamp' => $req->stamp
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route('home');
}



public function resetDatabase()
{
    if (!auth()->user()->is_admin) {
        abort(403);
    }

    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    DB::table('requests')->truncate();
    DB::table('users')->where('role', 'resident')->delete();

    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    return back()->with('success', 'Database reset successfully');
}

    public function send($id)
    {
        $request = LetterRequest::findOrFail($id);

        $request->sent_to_resident = true;
        $request->sent_at = now(); // optional but recommended

        $request->save();

        return response()->json([
            'success' => true
        ]);
    }
}