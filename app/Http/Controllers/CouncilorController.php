<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\LetterRequest;
use Cloudinary\Cloudinary;

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
        $letter = LetterRequest::findOrFail($id);

        $letter->update([
            'status' => 'Approved',
            'approved_by' => auth()->user()->name,
            'approved_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    // ================= REJECT =================
    public function reject(Request $req, $id)
    {
        $letter = LetterRequest::findOrFail($id);

        $letter->update([
            'status' => 'Rejected',
            'rejection_reason' => $req->reason
        ]);

        return response()->json(['success' => true]);
    }

    // ================= APPROVE WITH SIGNATURE + STAMP =================
 // ================= APPROVE WITH SIGNATURE + STAMP =================
  public function approveWithSignature(Request $request, $id)
{
    $request->validate([
        'signature' => 'nullable|string',
        'stamp' => 'nullable|image|max:2048',
    ]);

    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $letter = LetterRequest::findOrFail($id);

    // Cloudinary setup
    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'api_key'    => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
        ],
        'url' => ['secure' => true]
    ]);

    // ===== SIGNATURE (BASE64 → CLOUDINARY) =====
    if ($request->signature) {

        $upload = $cloudinary->uploadApi()->upload($request->signature, [
            'folder' => 'signatures'
        ]);

        $letter->signed_letter = $upload['secure_url'];
    }

    // ===== STAMP =====
    if ($request->hasFile('stamp')) {

        $upload = $cloudinary->uploadApi()->upload(
            $request->file('stamp')->getRealPath(),
            ['folder' => 'stamps']
        );

        $letter->stamp = $upload['secure_url'];

    } else {
        $letter->stamp = asset('images/default-stamp.png');
    }

    // ===== APPROVAL =====
    $letter->status = 'Approved';
    $letter->approved_at = now();
    $letter->approved_by = $user->name;
    $letter->save();

    return response()->json([
        'success' => true,
        'signed_letter' => $letter->signed_letter,
        'stamp' => $letter->stamp
    ]);
}

private function cloudinary()
{
    return new Cloudinary([
        'cloud' => [
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'api_key'    => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
        ],
    ]);
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