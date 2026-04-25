<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    // GET REQUESTS
    public function index()
    {
         $requests = LetterRequest::where('user_id', Auth::id())
         ->latest()
         ->get();
    return view('resident.dashboard.index', compact('requests'));
    }

    public function create()
    {
        return view('resident.request-letter');
    }

    // SAVE REQUEST
    public function store(Request $request)
    {
        $request->validate([
            'letter_type' => 'required|string',
            'purpose_type' => 'required|string',
            'address' => 'required|string',
           
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        LetterRequest::create([
    'user_id' => Auth::id(), // 🔥 FIXED
            'letter_type' => $request->letter_type,
            'purpose_type' => $request->purpose_type,
            'address' => $request->address,
           
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'Pending',
            'reference_number' => 'LR-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
]);

        return redirect()
        ->route('resident.dashboard')
        ->with('success', 'Request submitted successfully');

         
    }

    public function cancel($id)
{
    $request = LetterRequest::findOrFail($id);

    if ($request->user_id !== auth()->id()) {
        abort(403);
    }

    // Only allow cancel if still pending
    if ($request->status !== 'Pending') {
        return back()->with('error', 'Only pending requests can be cancelled');
    }

    $request->status = 'Cancelled';
    $request->save();

    return back()->with('success', 'Request cancelled successfully');
}
}