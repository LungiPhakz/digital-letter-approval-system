<?php

namespace App\Http\Controllers;

use App\Models\LetterRequest;

class AdminController extends Controller
{

public function dashboard()
{

$total = LetterRequest::count();
$pending = LetterRequest::where('status','pending')->count();
$approved = LetterRequest::where('status','approved')->count();

return view('admin.dashboard',compact(
'total','pending','approved'
));

}

public function requests()
{

$requests = LetterRequest::all();

return view('admin.manage-requests',compact('requests'));

}

public function approve($id)
{

$request = LetterRequest::find($id);
$request->status='approved';
$request->save();

return back();

}

public function reject($id)
{

$request = LetterRequest::find($id);
$request->status='rejected';
$request->save();

return back();

}
public function cleanupCancelled()
{
    LetterRequest::where('status', 'Cancelled')->delete();

    return back()->with('success', 'Cancelled requests deleted successfully');
}

}