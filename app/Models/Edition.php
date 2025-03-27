<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'theme',
        'regle',
        'lieu',
        'date'
    ];

    public function statistique()
    {
        return $this->hasOne(Statistique::class);
    }

    public function equipes()
    {
        return $this->hasMany(Equipe::class);
    }
    public function organisateurs()
    {
        return $this->hasMany(Organisateur::class);
    }
}
