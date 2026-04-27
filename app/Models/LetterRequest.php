<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterRequest extends Model
{
    //

    protected $fillable = [
    'user_id',
    'letter_type',
    'purpose_type',
    'address',
     'rejection_reason',
    'latitude',
    'longitude',
    'status',
    'reference_number'
];

public function letter()
{
    return $this->hasOne(Letter::class, 'request_id');
} 
public function user()
{
    return $this->belongsTo(User::class);
}

}
