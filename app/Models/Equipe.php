<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'edition_id',
        'name'
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function projet()
    {
        return $this->hasOne(Projet::class);
    }
}
