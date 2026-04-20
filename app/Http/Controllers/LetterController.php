<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LetterRequest;
use App\Models\Letter;

class LetterController extends Controller
{
    //

    public function download($id)
{
    $letter = Letter::findOrFail($id);

    $pdf = Pdf::loadView('letters.template', compact('letter'));

    return $pdf->download('letter.pdf');
}
}
