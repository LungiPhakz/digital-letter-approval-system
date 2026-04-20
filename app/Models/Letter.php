<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    //


    protected $fillable = [
        'request_id',
        'reference_number',
        'approved_by',
        'qr_code'
    ];

    public function request()
    {
        return $this->belongsTo(LetterRequest::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
