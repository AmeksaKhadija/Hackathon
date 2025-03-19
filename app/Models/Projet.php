<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;

    public function equipe()
    {
        return $this->belongsTo(Equipe::class);
    }

    public function jury()
    {
        return $this->hasOne(Jury::class);
    }
}
