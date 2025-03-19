<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member_jury extends Model
{
    use HasFactory;
    protected $fillable = [
        'random_email',
        'random_password'
    ];

    public function jury()
    {
        return $this->belongsTo(Jury::class);
    }
}
