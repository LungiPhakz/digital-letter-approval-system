<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    //
   public function privacy()
{
    return view('pages.privacy')->with('title', 'Privacy Policy');
}

public function terms()
{
    return view('pages.terms')->with('title', 'Terms of Service');
}

public function security()
{
    return view('pages.security')->with('title', 'Security');
}
}
