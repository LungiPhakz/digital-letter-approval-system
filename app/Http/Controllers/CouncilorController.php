<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\LetterRequest;

class CouncilorController extends Controller
{
    // ================= LOGIN =================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            $user = Auth::user();

            if ($user->role !== 'councilor' && $user->role !== 'admin') {
                Auth::logout();
                return back()->with('error', 'Access denied. Not a councilor.');
            }

            return redirect()->route('councilor.dashboard');
        }

        return back()->with('error', 'Invalid login details');
    }

    // ================= DASHBOARD =================
    public function dashboard(Request $request)
    {
        $query = LetterRequest::query();

        if ($request->search) {
            $query->where('reference_number', 'like', '%' . $request->search . '%');
        }

        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $requests = $query->with('user')->latest()->get();

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
   $req = LetterRequest::findOrFail($id);

        // ===== SAVE SIGNATURE =====
        if ($request->signature) {

           $image = str_replace('data:image/png;base64,', '', $request->signature);
            $image = str_replace(' ', '+', $image);

            $imageName = 'signatures/' . uniqid() . '.png'; 
            Storage::disk('public')->put($imageName, base64_decode($image));

            // Save FULL URL
           $signaturePath = '/storage/' . $imageName; $req->signed_letter = $signaturePath;
        }

        $defaultStamp = '/images/default-stamp.png';

// ===== SAVE STAMP =====
if ($request->hasFile('stamp')) {

    $stampPath = $request->file('stamp')->store('stamps', 'public');
    $req->stamp = '/storage/' . $stampPath;

} else {

    // fallback to default if nothing uploaded
    $req->stamp = $req->stamp ?? $defaultStamp;
}

       

        // ===== APPROVAL =====
        $req->status = 'Approved';
         $req->approved_at = now();
         $req->approved_by = auth()->user()->name; 
         
         $req->save();

        return response()->json([
    'success' => true,
    'message' => 'Signed and approved successfully',
    'signed_letter' => $req->signed_letter,
    'stamp' => $req->stamp
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